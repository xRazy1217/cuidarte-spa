#!/usr/bin/env bash
# Smoke test de solo lectura para Cuidarte Spa (no crea pedidos ni reservas).
#
# Uso:
#   tools/smoke.sh [BASE_URL] [--save archivo] [--compare archivo] [--ssh host]
#   BASE_URL por defecto: https://cuidartelaserena.cl
#   --save     guarda el resultado (ruta, código, título) como baseline
#   --compare  compara contra un baseline y falla si algo cambió
#   --ssh      host SSH: además revisa el php_errorlog del servidor (tamaño y últimas líneas)
# Salida: código 0 si todo OK, 1 si hay fallos.

BASE="https://cuidartelaserena.cl"; SAVE=""; CMP=""; SSHH=""
while [ $# -gt 0 ]; do
  case "$1" in
    --save) SAVE="$2"; shift 2;;
    --compare) CMP="$2"; shift 2;;
    --ssh) SSHH="$2"; shift 2;;
    http*) BASE="${1%/}"; shift;;
    *) echo "Argumento desconocido: $1"; exit 2;;
  esac
done

FAIL=0; OUT="$(mktemp)"; TMP="$(mktemp)"
ok()   { printf '  OK    %s\n' "$1"; }
bad()  { printf '  FALLO %s\n' "$1"; FAIL=$((FAIL+1)); }

# check RUTA "códigos aceptados separados por |"
check() {
  local path="$1" accept="$2" code title
  code=$(curl -s -m 25 -o "$TMP" -w '%{http_code}' "$BASE$path")
  title=$(grep -o '<title>[^<]*' "$TMP" | head -1 | sed 's/<title>//')
  if ! echo "|$accept|" | grep -q "|$code|"; then bad "$path -> $code (esperado $accept)"
  elif grep -qE 'Fatal error|Parse error|Stack trace|Uncaught |Warning: |Notice: |Deprecated: ' "$TMP"; then bad "$path -> $code pero el HTML contiene errores PHP"
  else ok "$path -> $code ${title:+| $title}"; fi
  printf '%s\t%s\t%s\n' "$path" "$code" "$title" >> "$OUT"
}

# blocked RUTA: no debe ser 200
blocked() {
  local code; code=$(curl -s -m 25 -o /dev/null -w '%{http_code}' "$BASE$1")
  if [ "$code" = "200" ]; then bad "EXPUESTO $1 -> 200"; else ok "protegido $1 -> $code"; fi
}

echo "== Smoke test: $BASE"
echo "-- Páginas públicas"
for p in / /productos /servicios /reservar /blog /nosotros /contacto /carrito /mi-cuenta/ingresar /registro; do check "$p" 200; done
check /checkout "200|302"
check /login "200|302"

echo "-- Detalle de productos (slugs leídos de /productos)"
SLUGS=$(curl -s -m 25 "$BASE/productos" | grep -oE '/producto/[a-z0-9-]+' | sort -u | head -3)
[ -z "$SLUGS" ] && bad "no se encontraron productos en /productos"
for s in $SLUGS; do check "$s" 200; done
ART=$(curl -s -m 25 "$BASE/blog" | grep -oE '/articulo/[a-z0-9-]+' | sort -u | head -1)
[ -n "$ART" ] && check "$ART" 200 || echo "  (sin artículos en /blog: se omite)"

echo "-- 404 propio (sin fatal)"
check /ruta-inexistente-smoke 404
grep -q 'no encontrada' "$TMP" && ok "página 404 con texto esperado" || bad "la página 404 no muestra 'no encontrada'"

echo "-- Admin sin sesión"
code=$(curl -s -m 25 -o /dev/null -w '%{http_code}' "$BASE/admin")
case "$code" in 301|302|303) ok "/admin redirige ($code)";; *) bad "/admin sin sesión -> $code (esperado redirección)";; esac

echo "-- Archivos sensibles no expuestos"
for p in /cuidarte-spa.zip /config/database.php /config/smtp.php /.git/HEAD /.git/config /database/cuidarte_spa.sql /php_errorlog /.env /app/controllers/Controller.php; do blocked "$p"; done

echo "-- Assets críticos"
CSS=$(curl -s -m 25 "$BASE/" | grep -oE 'href="[^"]*public/css/style\.css[^"]*"' | head -1 | sed 's/href="//;s/"$//')
[ -n "$CSS" ] && { c=$(curl -s -m 25 -o /dev/null -w '%{http_code}' "$CSS"); [ "$c" = 200 ] && ok "style.css -> 200" || bad "style.css -> $c"; } || bad "home sin link a style.css"
for u in $(curl -s -m 25 "$BASE/" | grep -oE 'src="'"$BASE"'[^"]*\.(png|jpe?g|webp|svg)"' | sed 's/src="//;s/"$//' | sort -u | head -5); do
  c=$(curl -s -m 25 -o /dev/null -w '%{http_code}' "$u"); [ "$c" = 200 ] && ok "imagen -> 200 ${u#$BASE}" || bad "imagen $u -> $c"
done

if [ -n "$SSHH" ]; then
  echo "-- Log de errores PHP en servidor ($SSHH)"
  ssh -o BatchMode=yes -o ConnectTimeout=15 "$SSHH" 'cd ~/www/*/public_html 2>/dev/null && for f in php_errorlog; do [ -f $f ] && { echo "  $f: $(wc -c < $f) bytes"; tail -3 $f | cut -c1-200; } || echo "  sin $f (sin errores nuevos)"; done' || bad "no se pudo consultar el log por SSH"
fi

if [ -n "$SAVE" ]; then cp "$OUT" "$SAVE"; echo "Baseline guardado en $SAVE"; fi
if [ -n "$CMP" ]; then
  echo "-- Comparación con baseline $CMP"
  if diff <(sort "$CMP") <(sort "$OUT") >/dev/null; then ok "idéntico al baseline (rutas, códigos y títulos)"
  else bad "difiere del baseline:"; diff <(sort "$CMP") <(sort "$OUT") | sed 's/^/    /'; fi
fi

rm -f "$OUT" "$TMP"
echo "== Resultado: $([ $FAIL -eq 0 ] && echo 'TODO OK' || echo "$FAIL fallo(s)")"
[ $FAIL -eq 0 ]
