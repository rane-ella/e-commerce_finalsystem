import { Link, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import Navbar from "~/components/Navbar";
import api from "~/services/api";

export default function AdminDashboard() {
    const navigate = useNavigate();
    const [stats, setStats] = useState({
        products: 0,
        orders: 0,
        users: 0
    });

    useEffect(() => {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            navigate("/login");
            return;
        }

        // Verify admin status and fetch stats
        Promise.all([
            api.get("/admin/products", { headers: { Authorization: `Bearer ${token}` } }),
            api.get("/admin/orders", { headers: { Authorization: `Bearer ${token}` } })
        ]).then(([productsRes, ordersRes]) => {
            setStats({
                products: productsRes.data.length,
                orders: ordersRes.data.length,
                users: 0 // Placeholder
            });
        }).catch(err => {
            console.error("Failed to load admin data", err);
            // If 403, redirect to home
            if (err.response && err.response.status === 403) {
                navigate("/");
            }
        });
    }, []);

    return (
        <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
            <Navbar />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8">Admin Dashboard</h1>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 className="text-lg font-medium text-[#666666]">Total Products</h3>
                        <p className="text-3xl font-bold text-[#333333] mt-2">{stats.products}</p>
                    </div>
                    <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 className="text-lg font-medium text-[#666666]">Total Orders</h3>
                        <p className="text-3xl font-bold text-[#333333] mt-2">{stats.orders}</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div className="w-16 h-16 bg-[#fcece9] rounded-full flex items-center justify-center mb-4">
                            <svg className="w-8 h-8 text-[#e76f51]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h2 className="text-xl font-bold text-[#333333] mb-2">Product Management</h2>
                        <p className="text-[#666666] mb-6">Add new products, update inventory, and manage pricing.</p>
                        <Link to="/admin/products" className="px-6 py-2 bg-[#e76f51] text-white rounded-lg hover:bg-[#d15a3a] transition-colors">
                            Go to Products
                        </Link>
                    </div>

                    <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div className="w-16 h-16 bg-[#fcece9] rounded-full flex items-center justify-center mb-4">
                            <svg className="w-8 h-8 text-[#e76f51]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h2 className="text-xl font-bold text-[#333333] mb-2">Order Management</h2>
                        <p className="text-[#666666] mb-6">View customer orders, update statuses, and process refunds.</p>
                        <Link to="/admin/orders" className="px-6 py-2 bg-[#e76f51] text-white rounded-lg hover:bg-[#d15a3a] transition-colors">
                            Go to Orders
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
