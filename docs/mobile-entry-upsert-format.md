# Formato completo para enviar entradas desde la app movil

Base API:

`https://bestiarioborealis.lat/bestiario/public/api`

Endpoint:

`POST /sync/creatures/upsert`

Headers:

```http
Authorization: Bearer {access_token}
Accept: application/json
Content-Type: application/json
```

## Identificacion

Cada entrada se identifica por:

```text
account_id + creature_uid
```

- `account_id`: id de cuenta devuelto por `/auth/login` o `/auth/register`.
- `creature_uid`: UUID estable generado por la app movil. No debe cambiar aunque se edite la ficha.

## Payload requerido

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
  "entry": {
    "title": "Borealis Examplitus",
    "classification": "Archivista arcano / Demiurgo taxonómico",
    "category": "Creador del bestiario",
    "threat_level": "Autoridad fundacional",
    "height": "1.86 m / variable bajo eclipse",
    "description": "No creó monstruos: creó una forma de recordarlos sin que devoraran la memoria de quienes los vieron.",
    "last_record": "Fue visto cerrando el Volumen Cero al amanecer, antes de dejar instrucciones para que otros completaran el archivo.",
    "status": "Activo / Fundador / Guardián editorial",
    "theme_key": "arcane",
    "origin": {
      "universe": "Archivo Boreal",
      "game": "BorsBestiario",
      "campaign": "Volumen Fundacional",
      "source": "Primer folio autógrafo",
      "region": "Observatorio de las Mareas Altas"
    },
    "subtitles": [
      "El Cartógrafo del Umbral",
      "Fundador del Bestiario",
      "La Pluma que Clasifica lo Imposible"
    ],
    "affinities": [
      "Aurora",
      "Tinta viva",
      "Cartografía astral",
      "Memoria náutica"
    ],
    "habitats": [
      "Biblioteca septentrional",
      "Cartas náuticas imposibles",
      "Observatorio sellado"
    ],
    "behaviors": [
      "Meticuloso",
      "Hospitalario",
      "Críptico",
      "Protector de registros",
      "Implacable con falsificaciones"
    ],
    "abilities": [
      {
        "name": "Pluma de indexación",
        "description": "Convierte testimonios dispersos en fichas coherentes sin borrar contradicciones útiles."
      },
      {
        "name": "Atlas de costas invisibles",
        "description": "Traza rutas hacia regiones que solo existen cuando una criatura ha sido nombrada."
      }
    ],
    "techniques": [
      {
        "name": "Catalogación de umbral",
        "description": "Analiza el origen de una entidad y fija sus rasgos antes de que cambie de forma."
      },
      {
        "name": "Ancla de tinta boreal",
        "description": "Crea un círculo de sellos que impide que un registro sea reescrito durante el combate."
      }
    ],
    "weaknesses": [
      {
        "description": "No puede destruir un registro una vez confirmado."
      },
      {
        "description": "Su autoridad depende de evidencia, testimonio y contraste."
      }
    ],
    "loot": [
      {
        "name": "Pluma de procedencia",
        "description": "Instrumento que firma mapas y fichas con tinta imposible de falsificar.",
        "rarity": "Reliquia"
      },
      {
        "name": "Fragmento del Volumen Cero",
        "description": "Hoja resistente al fuego, al agua y a la memoria alterada.",
        "rarity": "Legendario"
      }
    ],
    "stats": [
      {
        "name": "Erudición",
        "value": 97,
        "value_label": "Fundacional"
      },
      {
        "name": "Cartografía",
        "value": 93,
        "value_label": "Magistral"
      },
      {
        "name": "Combate directo",
        "value": 28,
        "value_label": "Evitado"
      }
    ],
    "vignettes": [
      {
        "title": "Primer folio",
        "description": "La página donde se definió la forma del dossier arcano."
      },
      {
        "title": "Carta náutica boreal",
        "description": "Mapa usado para ubicar criaturas que migran entre universos."
      }
    ],
    "scholar_notes": [
      {
        "note": "Borealis nunca firma al inicio. Prefiere que la evidencia hable primero."
      },
      {
        "note": "Su bestiario admite criaturas, aliados, entidades, errores y milagros con la misma disciplina."
      }
    ],
    "final_combat_scenario": "El enfrentamiento ocurre en el Observatorio de las Mareas Altas mientras el archivo gira como una carta náutica viva. Cada ronda abre un folio distinto: uno revela rutas, otro oculta nombres y el último obliga a decidir si preservar o cerrar el registro."
  }
}
```

## Campos obligatorios

En la raiz:

- `account_id`
- `creature_uid`
- `entry`

Dentro de `entry`:

- `title`
- `classification`
- `threat_level`
- `description`

## Campos opcionales soportados

Campos simples:

- `slug`
- `category`
- `height`
- `last_record`
- `status`
- `final_combat_scenario`
- `main_image_path`
- `primary_color`
- `accent_color`
- `parchment_tone`
- `frame_style`
- `published_at`
- `theme_key`

Objeto:

- `origin.universe`
- `origin.game`
- `origin.campaign`
- `origin.source`
- `origin.region`

Listas de strings:

- `subtitles`, máximo 3
- `affinities`
- `habitats`
- `behaviors`

Listas de objetos:

- `abilities[]`: `name`, `description`
- `techniques[]`: `name`, `description`
- `weaknesses[]`: `description`
- `loot[]`: `name`, `description`, `rarity`
- `stats[]`: `name`, `value`, `value_label`
- `vignettes[]`: `title`, `description`, `image_path`
- `scholar_notes[]`: `note`

## Respuesta esperada

Si crea:

`201 Created`

Si actualiza:

`200 OK`

Respuesta:

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
  "entry": {
    "title": "Borealis Examplitus",
    "account_id": 1,
    "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
    "server_id": 10,
    "classification": "Archivista arcano / Demiurgo taxonómico",
    "category": "Creador del bestiario",
    "threat_level": "Autoridad fundacional",
    "height": "1.86 m / variable bajo eclipse",
    "description": "...",
    "last_record": "...",
    "status": "...",
    "final_combat_scenario": "...",
    "theme_key": "arcane",
    "origin": {},
    "subtitles": [],
    "affinities": [],
    "habitats": [],
    "behaviors": [],
    "abilities": [],
    "techniques": [],
    "weaknesses": [],
    "loot": [],
    "stats": [],
    "vignettes": [],
    "scholar_notes": [],
    "updated_at": "2026-07-07T16:00:00.000000Z",
    "last_synced_at": "2026-07-07T16:00:00.000000Z"
  }
}
```

## Notas importantes para la app movil

- Enviar siempre todas las listas completas. El servidor reemplaza las listas anteriores por las recibidas.
- Si una lista viene vacia o no se envia, puede quedar vacia en servidor.
- `stats[].value` debe ser entero entre `0` y `100`.
- `theme_key` debe existir. Temas base: `arcane`, `marine`, `electro`, `fire`, `holy`, `shadow`.
- El servidor conserva el JSON original en `source_payload` como respaldo, pero la app debe leer la respuesta normalizada de `entry`.
