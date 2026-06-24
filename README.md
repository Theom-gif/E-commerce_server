# E-Commerce Backend API

This project is a Laravel backend for an e-commerce website. It provides real data for:

- products
- categories
- cart
- checkout
- wishlist
- orders
- reviews
- authentication
- a lightweight AI/store assistant endpoint

The frontend can connect to this backend through JSON API requests.

## Base URL

If you run the backend locally with Artisan, the API is usually available at:

```text
http://localhost:8000/api
```

Admin pages are under:

```text
http://localhost:8000/admin
```

## Setup

1. Install dependencies.
2. Copy `.env.example` to `.env`.
3. Set your database credentials in `.env`.
4. Run:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

## Frontend Connection

If your frontend is in React, Vue, Next.js, or another SPA, create a small API client and point it to the Laravel backend.

Example using Axios:

```js
import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api",
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
```

## Auth Flow

This backend uses Laravel Sanctum personal access tokens.

### Login

```http
POST /api/login
```

Request body:

```json
{
  "email": "user@example.com",
  "password": "password"
}
```

Response includes:

- `user`
- `token`

Save the token in `localStorage` or your preferred auth store, then send it in:

```http
Authorization: Bearer YOUR_TOKEN
```

### Register

```http
POST /api/register
```

### Logout

```http
POST /api/logout
```

Requires authentication.

## Main API Endpoints

### Public endpoints

```http
GET /api/categories
GET /api/categories/{category}
GET /api/products
GET /api/products/{id}
GET /api/products/search/{keyword}
GET /api/products/{id}/reviews
POST /api/register
POST /api/login
POST /api/assistant
```

### Authenticated endpoints

```http
GET /api/cart
POST /api/cart
PUT /api/cart/{cart}
DELETE /api/cart/{cart}

GET /api/wishlist
POST /api/wishlist/{productId}
DELETE /api/wishlist/{productId}

POST /api/checkout
GET /api/orders
GET /api/orders/{order}

GET /api/profile
PUT /api/profile
PUT /api/change-password
POST /api/logout
```

## Real Data Usage

### Get all categories

```js
const { data } = await api.get("/categories");
```

### Get all products

```js
const { data } = await api.get("/products");
```

### Filter products by category

```js
const { data } = await api.get("/products", {
  params: { category_id: 1 },
});
```

### Search products

```js
const { data } = await api.get("/products", {
  params: { search: "shoes" },
});
```

### Add item to cart

```js
await api.post("/cart", {
  product_id: 12,
  quantity: 2,
});
```

### Checkout

```js
const { data } = await api.post("/checkout");
```

## AI / Store Assistant

This backend includes a simple assistant endpoint that can help the frontend communicate with the store.

It can respond to requests like:

- "show me products"
- "what categories do you have?"
- "check my cart"
- "I want to checkout"

### Request

```http
POST /api/assistant
```

Example body:

```json
{
  "message": "show me running shoes"
}
```

Optional body:

```json
{
  "message": "I want to checkout",
  "limit": 5
}
```

### Response

The response includes:

- `intent`
- `message`
- `categories`
- `products`
- `actions`
- `query`

Example frontend usage:

```js
const { data } = await api.post("/assistant", {
  message: userInput,
});

setChatReply(data.message);
setSuggestedProducts(data.products);
setSuggestedCategories(data.categories);
setActions(data.actions);
```

### How to use it in the UI

You can build a chat box or assistant panel like this:

1. User types a message.
2. Frontend sends the message to `POST /api/assistant`.
3. Backend returns real product/category/cart guidance.
4. Frontend shows the response as chat text, product cards, or action buttons.

This assistant is stateless, so the frontend should store the conversation history if you want a chat-like experience.

## Suggested Frontend Structure

Recommended files in the frontend app:

```text
src/api/client.js
src/api/auth.js
src/api/products.js
src/api/cart.js
src/api/assistant.js
src/components/ProductCard.jsx
src/components/CategoryCard.jsx
src/components/ChatAssistant.jsx
```

## Example Assistant Client

```js
import api from "./client";

export async function askAssistant(message) {
  const { data } = await api.post("/assistant", { message });
  return data;
}
```

## Notes

- This backend currently uses bearer token authentication through Sanctum.
- If the frontend is on a different origin, make sure the browser can reach the backend API URL.
- If you want cookie-based SPA auth instead of bearer tokens, that can be added later.

## Tests

Run the backend tests with:

```bash
php artisan test
```

## Need a Frontend Starter?

If you want, I can also create:

1. a React frontend API service layer
2. a chat UI for the assistant
3. a full product listing and cart page connected to this backend
