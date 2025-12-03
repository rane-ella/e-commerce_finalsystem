import { type RouteConfig, index, route } from "@react-router/dev/routes";

export default [
    index("routes/_index.tsx"),
    route("login", "routes/login.tsx"),
    route("otp", "routes/otp.tsx"),
    route("dashboard", "routes/dashboard.tsx"),
    route("products", "routes/products.tsx"),
    route("products/:id", "routes/products.$id.tsx"),
    route("cart", "routes/cart.tsx"),
    route("checkout", "routes/checkout.tsx"),
    route("orders", "routes/orders.tsx"),
    route("categories/:slug", "routes/categories.$slug.tsx"),

    // Admin Routes
    route("admin/dashboard", "routes/admin.dashboard.tsx"),
    route("admin/products", "routes/admin.products.tsx"),
    route("admin/products/new", "routes/admin.products.new.tsx"),
    route("admin/products/:id", "routes/admin.products.$id.tsx"),
    route("admin/orders", "routes/admin.orders.tsx"),
] satisfies RouteConfig;
