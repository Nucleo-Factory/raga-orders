# API de Órdenes de Compra - Documentación

Este documento describe los endpoints disponibles para interactuar con las órdenes de compra (POs) en el sistema Raga-X Orders.

## Autenticación

La mayoría de los endpoints requieren autenticación mediante token (Bearer Token), el cual se puede generar en la cuenta de Raga Orders.

```
Authorization: Bearer <token>
Content-Type: application/json
```

## Endpoints Disponibles

### 1. Crear Orden de Compra

**URL:** `POST /api/purchase-orders`

**Descripción:** Este endpoint permite registrar una nueva orden de compra en el sistema.

**Request Body (JSON):**
```json
[
  {
    "general": {
      "order_number": "51742499",
      "vendor_id": "5003129",
      "currency": "USD",
      "incoterms": "FOB",
      "date_required_in_destination": "2025/07/29",
      "planned_hub_id": "EWR",
      "mode": "SEA",
      "ship_to_id": "BALTIMORE SPICE C.A.S.A.",
      "bill_to_id": "BALTIMORE SPICE CENTRAL AMERICA S.A.",
      "netValue": 32181.33
    },
    "items": [
      {
        "material": "20526004",
        "price_per_unit": 10.15,
        "peso_kg": 1383.48
      },
      {
        "material": "20526019",
        "price_per_unit": 6.28,
        "peso_kg": 816.48
      }
    ]
  }
]
```

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Purchase orders created successfully",
  "data": [
    {
      "order_number": "51742499",
      "id": 123,
      "status": "success"
    }
  ]
}
```

### 2. Obtener una Orden de Compra

**URL:** `GET /api/purchase-orders/{identifier}`

**Descripción:** Este endpoint permite obtener la información detallada de una orden de compra específica. El identificador puede ser el ID numérico o el número de orden.

**Parámetros de URL:**
- `identifier`: ID numérico o número de orden (string)

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Purchase order retrieved successfully",
  "data": {
    "id": 123,
    "order_number": "51742499",
    "status": "draft",
    "vendor": {
      "id": 45,
      "name": "Proveedor XYZ"
    },
    "shipTo": {
      "id": 12,
      "name": "BALTIMORE SPICE C.A.S.A."
    },
    "billTo": {
      "id": 8,
      "name": "BALTIMORE SPICE CENTRAL AMERICA S.A."
    },
    "products": [
      {
        "id": 67,
        "material_id": "20526004",
        "short_text": "Product 20526004",
        "pivot": {
          "quantity": 1383,
          "unit_price": 10.15
        }
      }
    ],
    "created_at": "2023-06-15T10:30:00.000000Z",
    "updated_at": "2023-06-15T10:30:00.000000Z"
  }
}
```

### 3. Listar Órdenes de Compra

**URL:** `GET /api/purchase-orders`

**Descripción:** Este endpoint permite listar todas las órdenes de compra con paginación y filtros.

**Parámetros de Consulta:**
- `vendor_id`: Filtrar por ID del proveedor
- `status`: Filtrar por estado (draft, pending, in_progress, completed, cancelled)
- `order_number`: Buscar por número de orden (búsqueda parcial)
- `date_from`: Filtrar por fecha desde (formato YYYY-MM-DD)
- `date_to`: Filtrar por fecha hasta (formato YYYY-MM-DD)
- `kanban_status_id`: Filtrar por ID de estado Kanban
- `sort_by`: Campo para ordenar resultados (default: created_at)
- `sort_direction`: Dirección de ordenamiento (asc, desc) (default: desc)
- `per_page`: Número de resultados por página (default: 15)

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Purchase orders retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 123,
        "order_number": "51742499",
        "status": "draft",
        "vendor": {
          "id": 45,
          "name": "Proveedor XYZ"
        },
        "shipTo": {
          "id": 12,
          "name": "BALTIMORE SPICE C.A.S.A."
        },
        "billTo": {
          "id": 8,
          "name": "BALTIMORE SPICE CENTRAL AMERICA S.A."
        },
        "created_at": "2023-06-15T10:30:00.000000Z",
        "updated_at": "2023-06-15T10:30:00.000000Z"
      }
    ],
    "first_page_url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=5",
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=2",
        "label": "2",
        "active": false
      },
      {
        "url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "next_page_url": "http://cliente.orders.raga-x.ai/api/purchase-orders?page=2",
    "path": "http://cliente.orders.raga-x.ai/api/purchase-orders",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 75
  }
}
```

### 4. Actualizar Estado de una Orden de Compra

**URL:** `PATCH /api/purchase-orders/{identifier}/status`

**Descripción:** Este endpoint permite actualizar el estado de una orden de compra específica.

**Parámetros de URL:**
- `identifier`: ID numérico o número de orden (string)

**Request Body (JSON):**
```json
{
  "status": "in_progress",
  "notes": "Orden en proceso de preparación",
  "kanban_status_id": 3
}
```

**Campos disponibles:**
- `status` (requerido): Nuevo estado de la orden (draft, pending, in_progress, completed, cancelled)
- `notes` (opcional): Notas adicionales sobre el cambio de estado
- `kanban_status_id` (opcional): ID del nuevo estado Kanban

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Purchase order status updated successfully",
  "data": {
    "id": 123,
    "order_number": "51742499",
    "status": "in_progress",
    "notes": "Orden en proceso de preparación",
    "kanban_status": {
      "id": 3,
      "name": "En Proceso"
    },
    "updated_at": "2023-06-16T14:25:00.000000Z"
  }
}
```

### 5. Agregar Comentario a una Orden de Compra

**URL:** `POST /api/purchase-orders/{identifier}/comments`

**Descripción:** Este endpoint permite agregar un comentario a una orden de compra específica.

**Parámetros de URL:**
- `identifier`: ID numérico o número de orden (string)

**Request Body (JSON):**
```json
{
  "comment": "El proveedor ha confirmado la fecha de envío para el 15 de julio",
  "user_id": 5
}
```

**Campos requeridos:**
- `comment`: Texto del comentario
- `user_id`: ID del usuario que realiza el comentario

**Respuesta Exitosa:**
```json
{
  "success": true,
  "message": "Comment added successfully",
  "data": {
    "id": 45,
    "purchase_order_id": 123,
    "user_id": 5,
    "comment": "El proveedor ha confirmado la fecha de envío para el 15 de julio",
    "created_at": "2023-06-16T15:30:00.000000Z",
    "user": {
      "id": 5,
      "name": "John Doe",
      "email": "john.doe@example.com"
    }
  }
}
```

## Códigos de Estado HTTP

- `200 OK`: La solicitud se ha completado correctamente
- `201 Created`: El recurso se ha creado correctamente
- `400 Bad Request`: La solicitud contiene datos inválidos
- `401 Unauthorized`: No se ha proporcionado un token válido
- `404 Not Found`: El recurso solicitado no existe
- `422 Unprocessable Entity`: Error de validación en los datos enviados
- `500 Internal Server Error`: Error interno del servidor
