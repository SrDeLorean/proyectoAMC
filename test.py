import requests
import base64


#images/users/6882d32e916eb.png
#images/users/6882d2543aa9d.jpg
# --- Paso 1: Obtener la URL ---
image_url = "https://shape-kit-scientific-mt.trycloudflare.com/images/users/6882d2543aa9d.jpg"
lm_studio_url = "http://192.168.3.191:1234/v1/chat/completions"

# --- Paso 2: Descargar la Imagen ---
try:
    response = requests.get(image_url)
    response.raise_for_status()
    image_data = response.content
    content_type = response.headers.get('content-type', 'image/png')
    base64_image = base64.b64encode(image_data).decode('utf-8')
except requests.exceptions.RequestException as e:
    print(f"Error al descargar la imagen: {e}")
    exit()

# --- Paso 3: Visualizar la imagen en base64 en HTML ---
html_file = "ver_imagen.html"
with open(html_file, "w") as f:
    f.write(f"""
    <html>
    <body>
        <h3>Vista previa de la imagen (base64):</h3>
        <img src="data:{content_type};base64,{base64_image}" style="max-width: 600px; border: 1px solid black;">
    </body>
    </html>
    """)
print(f"Imagen codificada guardada en: {html_file} (abre en tu navegador para verla)")

# --- Paso 4: Instrucción de extracción (prompt) ---
content_text = (
    "Eres un asistente experto en extraer datos de imágenes con estadísticas de partidos de fútbol virtual.\n\n"
    "Tu objetivo es extraer únicamente los textos visibles en color blanco. Ignora cualquier otro color, sin importar su posición o tamaño.\n\n"
    "Los textos en blanco contienen datos importantes como nombres de equipos, puntajes y estadísticas. Todos los valores válidos son números enteros o decimales desde 0 hasta centenas o más, incluyendo porcentajes.\n\n"
    "Si un dato está incompleto o no está claramente en texto blanco, debe ser devuelto como null.\n\n"
    "Extrae exactamente los siguientes datos:\n"
    "- Nombre del equipo local\n"
    "- Nombre del equipo visitante\n"
    "- Goles del equipo local\n"
    "- Goles del equipo visitante\n"
    "- Tiempo del partido (en formato mm:ss o similar)\n\n"
    "Además, extrae para cada equipo (local y visitante), únicamente desde texto blanco:\n"
    "posesion (%), recuperacion de balon (segundos), tiros, goles esperados (decimal), pases, entradas, entradas con exito, recuperaciones, atajadas, "
    "faltas cometidas, fuera de juego, tiros de esquina, tiros libres, penales, tarjetas amarillas, tasa de exito en regates (%), precision en tiros (%), precision en pases (%).\n\n"
    "Devuelve solo un JSON en el siguiente formato. No incluyas explicaciones ni otro texto:\n\n"
    "{\n"
    '  "partido": {\n'
    '    "equipo_local": "...",\n'
    '    "equipo_visitante": "...",\n'
    '    "goles_local": ..., \n'
    '    "goles_visitante": ..., \n'
    '    "tiempo": "..." \n'
    '  },\n'
    '  "estadisticas": {\n'
    '    "equipo_local": {\n'
    '      "posesion": ..., \n'
    '      "recuperacion_balon": ..., \n'
    '      "tiros": ..., \n'
    '      "goles_esperados": ..., \n'
    '      "pases": ..., \n'
    '      "entradas": ..., \n'
    '      "entradas_con_exito": ..., \n'
    '      "recuperaciones": ..., \n'
    '      "atajadas": ..., \n'
    '      "faltas_cometidas": ..., \n'
    '      "fuera_de_juego": ..., \n'
    '      "tiros_de_esquina": ..., \n'
    '      "tiros_libres": ..., \n'
    '      "penales": ..., \n'
    '      "tarjetas_amarillas": ..., \n'
    '      "tasa_exito_regates": ..., \n'
    '      "precision_tiros": ..., \n'
    '      "precision_pases": ... \n'
    '    },\n'
    '    "equipo_visitante": {\n'
    '      "posesion": ..., \n'
    '      "recuperacion_balon": ..., \n'
    '      "tiros": ..., \n'
    '      "goles_esperados": ..., \n'
    '      "pases": ..., \n'
    '      "entradas": ..., \n'
    '      "entradas_con_exito": ..., \n'
    '      "recuperaciones": ..., \n'
    '      "atajadas": ..., \n'
    '      "faltas_cometidas": ..., \n'
    '      "fuera_de_juego": ..., \n'
    '      "tiros_de_esquina": ..., \n'
    '      "tiros_libres": ..., \n'
    '      "penales": ..., \n'
    '      "tarjetas_amarillas": ..., \n'
    '      "tasa_exito_regates": ..., \n'
    '      "precision_tiros": ..., \n'
    '      "precision_pases": ... \n'
    '    }\n'
    '  }\n'
    "}"
)

# --- Paso 5: Armar payload y enviar a LM Studio ---
json_payload = {
    "model": "google/gemma-3-12b",
    "messages": [
        {
            "role": "user",
            "content": [
                {
                    "type": "text",
                    "text": content_text
                },
                {
                    "type": "image_url",
                    "image_url": {
                        "url": f"data:{content_type};base64,{base64_image}"
                    }
                }
            ]
        }
    ],
    "temperature": 0.7,
    "max_tokens": 1024,
    "stream": False
}

# --- Paso 6: Enviar solicitud a LM Studio ---
try:
    response = requests.post(lm_studio_url, json=json_payload)
    response.raise_for_status()
    print("Respuesta del modelo:")
    print(response.json())
except requests.exceptions.RequestException as e:
    print(f"Error al contactar LM Studio: {e}")
