# Prompt para implementar sincronizacion movil Bestiario Borealis

Usa esta especificacion para conectar la app movil con el backend Laravel alojado en:

`https://bestiarioborealis.lat/bestiario`

Base API:

`https://bestiarioborealis.lat/bestiario/api`

Todas las rutas protegidas usan Sanctum con header:

```http
Authorization: Bearer {access_token}
Accept: application/json
Content-Type: application/json
```

## Crear cuenta

`POST /auth/register`

Body:

```json
{
  "name": "Nombre del usuario",
  "email": "usuario@example.com",
  "password": "password-seguro",
  "password_confirmation": "password-seguro",
  "device_name": "iphone-15-pro"
}
```

Respuesta `201`:

```json
{
  "token_type": "Bearer",
  "access_token": "TOKEN",
  "user": {
    "account_id": 1,
    "name": "Nombre del usuario",
    "email": "usuario@example.com",
    "roles": ["viewer"]
  }
}
```

Guarda `access_token` seguro en el dispositivo y guarda `account_id` como id de cuenta remota.

## Login

`POST /auth/login`

Body:

```json
{
  "email": "usuario@example.com",
  "password": "password-seguro",
  "device_name": "iphone-15-pro"
}
```

Respuesta `200`: igual a registro.

## Usuario actual

`GET /auth/me`

Respuesta:

```json
{
  "user": {
    "account_id": 1,
    "name": "Nombre",
    "email": "usuario@example.com",
    "roles": ["viewer"]
  }
}
```

## Logout del token actual

`POST /auth/logout`

Respuesta `204`.

## Modelo mental de sincronizacion

Cada criatura local debe tener un id estable generado por la app movil, por ejemplo un UUID:

`creature_uid = "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14"`

El backend lo guarda como `sync_uid` y lo devuelve como `creature_uid`.

La dupla que identifica una criatura es:

```text
account_id + creature_uid
```

No uses el `server_id` como identificador local principal; solo es informativo.

## Verificar si una criatura existe en servidor

`POST /sync/creatures/exists`

Body:

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14"
}
```

Respuesta si existe:

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
  "exists": true,
  "entry": {
    "account_id": 1,
    "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
    "server_id": 10,
    "title": "Borealis Examplitus",
    "classification": "Quimera",
    "threat_level": "Media",
    "description": "...",
    "updated_at": "2026-07-07T15:00:00.000000Z"
  }
}
```

Si no existe:

```json
{
  "account_id": 1,
  "creature_uid": "uuid-local",
  "exists": false,
  "entry": null
}
```

## Descargar una criatura concreta

`GET /sync/creatures/{creature_uid}?account_id=1`

Usa esta ruta cuando el servidor dice que existe y la app no la tiene localmente.

## Obtener todas las criaturas de la cuenta

`GET /sync/creatures?account_id=1`

Opcional incremental:

`GET /sync/creatures?account_id=1&since=2026-07-07T15:00:00Z`

Respuesta:

```json
{
  "account_id": 1,
  "server_time": "2026-07-07T15:10:00.000000Z",
  "entries": []
}
```

Guarda `server_time` como checkpoint para la siguiente sync incremental.

## Calcular diferencias entre dispositivo y servidor

`POST /sync/creatures/diff`

Body:

```json
{
  "account_id": 1,
  "local_creature_uids": [
    "uuid-local-1",
    "uuid-local-2"
  ]
}
```

Respuesta:

```json
{
  "account_id": 1,
  "server_time": "2026-07-07T15:10:00.000000Z",
  "existing_uids": ["uuid-local-1"],
  "missing_on_device": [
    {
      "account_id": 1,
      "creature_uid": "uuid-que-solo-esta-en-web",
      "title": "Criatura web",
      "classification": "Entidad",
      "threat_level": "Alta",
      "description": "..."
    }
  ],
  "missing_on_server_uids": ["uuid-local-2"]
}
```

Uso recomendado:

1. La app envia todos sus `local_creature_uids`.
2. Inserta/actualiza localmente cada item de `missing_on_device`.
3. Para cada `missing_on_server_uids`, subir la criatura local con `upsert`.

## Crear o actualizar criatura desde movil

`POST /sync/creatures/upsert`

Body minimo:

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
  "entry": {
    "title": "Nombre de criatura",
    "classification": "Criatura",
    "category": "Bestia",
    "threat_level": "Media",
    "height": "2 m",
    "description": "Descripcion larga",
    "last_record": "Ultimo avistamiento",
    "status": "borrador",
    "theme_key": "arcane",
    "origin": {
      "universe": "Bestiario Borealis",
      "game": "Mobile",
      "campaign": "Local",
      "source": "Dispositivo",
      "region": "Norte"
    },
    "subtitles": ["Alias uno"],
    "affinities": ["Arcano"],
    "habitats": ["Bosque frio"],
    "behaviors": ["Observa desde lejos"],
    "abilities": [
      {"name": "Eco boreal", "description": "Duplica sonidos."}
    ],
    "techniques": [
      {"name": "Zarpazo frio", "description": "Ataque de hielo."}
    ],
    "weaknesses": [
      {"description": "Fuego sagrado."}
    ],
    "loot": [
      {"name": "Escama", "description": "Material raro.", "rarity": "raro"}
    ],
    "stats": [
      {"name": "Fuerza", "value": 70, "value_label": "Alta"}
    ],
    "vignettes": [
      {"title": "Avistamiento", "description": "Se ve bajo la luna."}
    ],
    "scholar_notes": [
      {"note": "No alimentar despues de medianoche."}
    ]
  }
}
```

Respuesta `200` o `201`:

```json
{
  "account_id": 1,
  "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
  "entry": {
    "account_id": 1,
    "creature_uid": "8c7f4f7a-5dd9-4a8c-8b4e-5ed2b6f87b14",
    "server_id": 10,
    "title": "Nombre de criatura",
    "updated_at": "2026-07-07T15:10:00.000000Z"
  }
}
```

## Seguridad y errores

- Si `account_id` no pertenece al token: `403`.
- Si falta token: `401`.
- Si una validacion falla: `422`.
- `theme_key` debe existir; temas base: `arcane`, `marine`, `electro`, `fire`, `holy`, `shadow`.

## Flujo recomendado al abrir la app

1. Si no hay token: mostrar login/registro.
2. Hacer `GET /auth/me`.
3. Leer `account_id`.
4. Enviar `POST /sync/creatures/diff` con todos los UUID locales.
5. Guardar localmente `missing_on_device`.
6. Subir con `upsert` los UUID en `missing_on_server_uids`.
7. Guardar `server_time` como ultimo checkpoint.

## Flujo recomendado para guardar una ficha local

1. Crear `creature_uid` UUID si la ficha no tiene.
2. Guardar localmente inmediatamente.
3. Intentar `POST /sync/creatures/upsert`.
4. Si falla por red, marcar pendiente de subida.
5. Reintentar en el proximo ciclo de sync.
