import { Link, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import Navbar from "~/components/Navbar";
import api from "~/services/api";

export default function AdminOrders() {
    const navigate = useNavigate();
    const [orders, setOrders] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchOrders();
    }, []);

    const fetchOrders = async () => {
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                navigate("/login");
                return;
            }

            const response = await api.get("/admin/orders", {
                headers: { Authorization: `Bearer ${token}` }
            });
            setOrders(response.data || []);
        } catch (err) {
            console.error("Failed to fetch orders", err);
        } finally {
            setLoading(false);
        }
    };

    const handleStatusChange = async (orderId: number, newStatus: string) => {
        try {
            const token = localStorage.getItem('auth_token');
            await api.patch(`/admin/orders/${orderId}`, { status: newStatus }, {
                headers: { Authorization: `Bearer ${token}` }
            });
            // Update local state
            setOrders(orders.map(o => o.id === orderId ? { ...o, status: newStatus } : o));
            alert("Order status updated successfully.");
        } catch (err) {
            console.error("Failed to update order status", err);
            alert("Failed to update order status.");
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
                <Navbar />
                <div className="flex justify-center items-center h-64">
                    <p className="text-xl text-[#666666]">Loading orders...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
            <Navbar />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <button
                    onClick={() => navigate(-1)}
                    className="flex items-center text-[#666666] hover:text-[#e76f51] transition-colors mb-8"
                >
                    <svg className="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>

                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8">Manage Orders</h1>

                <div className="space-y-6">
                    {orders.map((order) => (
                        <div key={order.id} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <div className="flex flex-col md:flex-row justify-between mb-4 border-b border-gray-100 pb-4">
                                <div>
                                    <p className="text-sm text-[#666666]">Order ID: <span className="font-bold text-[#333333]">#{order.id}</span></p>
                                    <p className="text-sm text-[#666666]">Customer: <span className="font-medium text-[#333333]">{order.user?.name}</span></p>
                                    <p className="text-sm text-[#666666]">Date: <span className="font-medium text-[#333333]">{new Date(order.created_at).toLocaleDateString()}</span></p>
                                    <p className="text-sm text-[#666666]">Payment Method: <span className="font-medium text-[#333333] capitalize">{order.payment_method === 'card' ? 'PayMongo' : order.payment_method}</span></p>
                                </div>
                                <div className="mt-4 md:mt-0 flex flex-col items-end gap-2">
                                    <p className="text-sm text-[#666666]">Total: <span className="font-bold text-[#e76f51]">₱{parseFloat(order.total_amount).toFixed(2)}</span></p>
                                    <div className="flex items-center gap-2">
                                        <label htmlFor={`status-${order.id}`} className="text-sm text-[#666666]">Status:</label>
                                        <select
                                            id={`status-${order.id}`}
                                            value={order.status}
                                            onChange={(e) => handleStatusChange(order.id, e.target.value)}
                                            disabled={order.status === 'canceled' || order.status === 'completed'}
                                            className="text-sm border-gray-300 rounded-md shadow-sm focus:border-[#e76f51] focus:ring focus:ring-[#e76f51] focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500"
                                        >
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="out_for_delivery">Out for Delivery</option>
                                            {order.status === 'completed' && <option value="completed">Completed</option>}
                                            {order.status === 'canceled' && <option value="canceled">Canceled</option>}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <h4 className="text-sm font-medium text-[#333333]">Items:</h4>
                                <ul className="divide-y divide-gray-100">
                                    {order.items && order.items.map((item: any) => (
                                        <li key={item.id} className="py-2 flex justify-between text-sm">
                                            <span className="text-[#666666]">{item.product?.name || 'Unknown Product'} (x{item.quantity})</span>
                                            <span className="font-medium text-[#333333]">₱{parseFloat(item.price).toFixed(2)}</span>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {order.notes && (
                                <div className="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p className="text-sm text-[#666666]"><span className="font-medium">Notes:</span> {order.notes}</p>
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
