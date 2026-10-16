#!/usr/bin/env bash
# Smoke test de solo lectura para Cuidarte Spa (no crea pedidos ni reservas).
#
# Uso:
#   tools/smoke.sh [BASE_URL] [--save archivo] [--compare archivo] [--ssh host]
#   BASE_URL por defecto: https://cuidartelaserena.cl
#   --save     guarda el resultado (ruta, código, título) como baseline
#   --compare  compara contra un baseline y falla si algo cambió
#   --ssh      host SSH: además revisa el log de errores PHP del servidor
# Salida: 0 todo OK, 1 hay fallos, 3 inconcluso (SiteGround pidió captcha por exceso de peticiones).

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

FAIL=0; INC=0; OUT="$(mktemp)"; TMP="$(mktemp)"; HDR="$(mktemp)"
ok()   { printf '  OK    %s\n' "$1"; }
bad()  { printf '  FALLO %s\n' "$1"; FAIL=$((FAIL+1)); }
# SiteGround responde 202 + cabecera SG-Captcha si detecta demasiadas peticiones: no es el sitio roto.
# No se intenta saltar el captcha: se marca inconcluso y se reintenta más tarde.
inc()  { printf '  ?     %s (SG-Captcha: inconcluso)\n' "$1"; INC=$((INC+1)); }
captcha() { grep -qi '^SG-Captcha' "$HDR"; }
get()  { sleep 0.4; curl -s -m 25 -D "$HDR" -o "$2" -w '%{http_code}' "$1"; }

# check RUTA "códigos aceptados separados por |"
check() {
  local path="$1" accept="$2" code title
  code=$(get "$BASE$path" "$TMP")
  if captcha; then inc "$path"; return; fi
  title=$(grep -o '<title>[^<]*' "$TMP" | head -1 | sed 's/<title>//')
  if ! echo "|$accept|" | grep -q "|$code|"; then bad "$path -> $code (esperado $accept)"
  elif grep -qE 'Fatal error|Parse error|Stack trace|Uncaught |Warning: |Notice: |Deprecated: ' "$TMP"; then bad "$path -> $code pero el HTML contiene errores PHP"
  else ok "$path -> $code ${title:+| $title}"; fi
  printf '%s\t%s\t%s\n' "$path" "$code" "$title" >> "$OUT"
}

# blocked RUTA: no debe responder 200
blocked() {
  local code; code=$(get "$BASE$1" /dev/null)
  if captcha; then inc "$1"; return; fi
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
if [ -n "$ART" ]; then check "$ART" 200; else echo "  (sin artículos en /blog: se omite)"; fi

echo "-- 404 propio (sin fatal)"
check /ruta-inexistente-smoke 404
if grep -q 'no encontrada' "$TMP"; then ok "página 404 con texto esperado"; else bad "la página 404 no muestra 'no encontrada'"; fi

echo "-- Admin sin sesión"
code=$(get "$BASE/admin" /dev/null)
if captcha; then inc /admin; else
  case "$code" in 301|302|303) ok "/admin redirige ($code)";; *) bad "/admin sin sesión -> $code (esperado redirección)";; esac
fi

echo "-- Archivos sensibles no expuestos"
for p in /cuidarte-spa.zip /config/database.php /config/smtp.php /.git/HEAD /.git/config /database/cuidarte_spa.sql /php_errorlog /.env /app/controllers/Controller.php /CLAUDE.md /tools/smoke.sh; do blocked "$p"; done

echo "-- Assets críticos"
HOME_HTML=$(curl -s -m 25 "$BASE/")
CSS=$(echo "$HOME_HTML" | grep -oE 'href="[^"]*public/css/style\.css[^"]*"' | head -1 | sed 's/href="//;s/"$//')
if [ -n "$CSS" ]; then c=$(get "$CSS" /dev/null); if captcha; then inc style.css; elif [ "$c" = 200 ]; then ok "style.css -> 200"; else bad "style.css -> $c"; fi
else bad "home sin link a style.css"; fi
for u in $(echo "$HOME_HTML" | grep -oE 'src="'"$BASE"'[^"]*\.(png|jpe?g|webp|svg)"' | sed 's/src="//;s/"$//' | sort -u | head -5); do
  c=$(get "$u" /dev/null)
  if captcha; then inc "${u#$BASE}"; elif [ "$c" = 200 ]; then ok "imagen -> 200 ${u#$BASE}"; else bad "imagen $u -> $c"; fi
done

if [ -n "$SSHH" ]; then
  echo "-- Log de errores PHP en servidor ($SSHH)"
  ssh -o BatchMode=yes -o ConnectTimeout=15 "$SSHH" 'cd ~/www/*/ 2>/dev/null && f=logs/php_errors.log && { [ -s $f ] && { echo "  $f: $(wc -c < $f) bytes"; tail -3 $f | cut -c1-200; } || echo "  sin errores PHP registrados"; }' || bad "no se pudo consultar el log por SSH"
fi

if [ -n "$SAVE" ] && [ $INC -eq 0 ]; then cp "$OUT" "$SAVE"; echo "Baseline guardado en $SAVE"; fi
if [ -n "$CMP" ] && [ $INC -eq 0 ]; then
  echo "-- Comparación con baseline $CMP"
  if diff <(sort "$CMP") <(sort "$OUT") >/dev/null; then ok "idéntico al baseline (rutas, códigos y títulos)"
  else bad "difiere del baseline:"; diff <(sort "$CMP") <(sort "$OUT") | sed 's/^/    /'; fi
fi

rm -f "$OUT" "$TMP" "$HDR"
if [ $INC -gt 0 ]; then echo "== Resultado: INCONCLUSO ($INC rutas con SG-Captcha; espera unos minutos y reintenta)"; exit 3; fi
echo "== Resultado: $([ $FAIL -eq 0 ] && echo 'TODO OK' || echo "$FAIL fallo(s)")"
[ $FAIL -eq 0 ]
