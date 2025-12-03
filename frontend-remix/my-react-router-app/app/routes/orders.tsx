import { useLoaderData, Link, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export default function Orders() {
    const [orders, setOrders] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

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

            const response = await api.get("/orders", {
                headers: { Authorization: `Bearer ${token}` }
            });
            setOrders(response.data || []);
        } catch (err) {
            console.error("Failed to fetch orders", err);
        } finally {
            setLoading(false);
        }
    };

    const [cancelModalOpen, setCancelModalOpen] = useState(false);
    const [selectedOrderId, setSelectedOrderId] = useState<number | null>(null);
    const [cancelReason, setCancelReason] = useState("");
    const [customReason, setCustomReason] = useState("");

    const cancelReasons = [
        "Change of mind",
        "Found a better price",
        "Ordered by mistake",
        "Shipping cost too high",
        "Delivery time too long",
        "Other"
    ];

    const openCancelModal = (orderId: number) => {
        setSelectedOrderId(orderId);
        setCancelReason("");
        setCustomReason("");
        setCancelModalOpen(true);
    };

    const handleCancelOrder = async () => {
        if (!selectedOrderId) return;

        try {
            const token = localStorage.getItem('auth_token');
            await api.patch(`/orders/${selectedOrderId}/cancel`, { reason: cancelReason === "Other" ? customReason : cancelReason }, {
                headers: { Authorization: `Bearer ${token}` }
            });

            // Update local state
            setOrders(orders.map(o => o.id === selectedOrderId ? { ...o, status: 'canceled' } : o));
            setCancelModalOpen(false);
            setCancelReason("");
            setCustomReason("");
            alert("Order canceled successfully.");
        } catch (err) {
            console.error("Failed to cancel order", err);
            alert("Failed to cancel order.");
        }
    };

    const handleCompleteOrder = async (orderId: number) => {
        if (!confirm("Are you sure you have received this order? This action cannot be undone.")) return;

        try {
            const token = localStorage.getItem('auth_token');
            await api.patch(`/orders/${orderId}/complete`, {}, {
                headers: { Authorization: `Bearer ${token}` }
            });

            // Update local state
            setOrders(orders.map(o => o.id === orderId ? { ...o, status: 'completed' } : o));
            alert("Order marked as completed.");
        } catch (err) {
            console.error("Failed to complete order", err);
            alert("Failed to complete order.");
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
                <Navbar />
                <div className="flex justify-center items-center h-64">
                    <p className="text-xl text-[#666666]">Loading purchases...</p>
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

                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8">My Purchases</h1>

                {orders.length === 0 ? (
                    <div className="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <p className="text-xl text-[#666666] mb-6">You haven't placed any orders yet.</p>
                        <Link
                            to="/products"
                            className="inline-block px-8 py-3 bg-[#e76f51] text-white font-bold rounded-xl hover:bg-[#d15a3a] transition-colors"
                        >
                            Start Shopping
                        </Link>
                    </div>
                ) : (
                    <div className="space-y-6">
                        {orders.map((order) => (
                            <div key={order.id} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <div className="flex flex-col sm:flex-row justify-between mb-4 border-b border-gray-100 pb-4">
                                    <div>
                                        <p className="text-sm text-[#666666]">Order ID</p>
                                        <p className="font-bold text-[#333333]">#{order.id}</p>
                                    </div>
                                    <div className="mt-2 sm:mt-0">
                                        <p className="text-sm text-[#666666]">Date</p>
                                        <p className="font-medium text-[#333333]">{new Date(order.created_at).toLocaleDateString()}</p>
                                    </div>
                                    <div className="mt-2 sm:mt-0">
                                        <p className="text-sm text-[#666666]">Payment Method</p>
                                        <p className="font-medium text-[#333333] capitalize">{order.payment_method === 'card' ? 'PayMongo' : order.payment_method}</p>
                                    </div>
                                    <div className="mt-2 sm:mt-0">
                                        <p className="text-sm text-[#666666]">Status</p>
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${order.status === 'completed' ? 'bg-green-100 text-green-800' :
                                            order.status === 'cancelled' || order.status === 'canceled' ? 'bg-red-100 text-red-800' :
                                                'bg-yellow-100 text-yellow-800'
                                            }`}>
                                            {order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                        </span>
                                    </div>
                                    <div className="mt-2 sm:mt-0 flex flex-col items-end gap-2">
                                        <div>
                                            <p className="text-sm text-[#666666]">Total</p>
                                            <p className="font-bold text-[#e76f51]">₱{parseFloat(order.total_amount).toFixed(2)}</p>
                                        </div>
                                        {order.status === 'pending' && (
                                            <button
                                                onClick={() => openCancelModal(order.id)}
                                                className="text-sm text-red-600 hover:text-red-800 font-medium underline"
                                            >
                                                Cancel Order
                                            </button>
                                        )}
                                        {order.status !== 'pending' && order.status !== 'canceled' && order.status !== 'completed' && (
                                            <button
                                                onClick={() => handleCompleteOrder(order.id)}
                                                className="text-sm text-green-600 hover:text-green-800 font-medium underline"
                                            >
                                                Order Received
                                            </button>
                                        )}
                                    </div>
                                </div>

                                <div className="space-y-4">
                                    {order.items && order.items.map((item: any) => (
                                        <div key={item.id} className="flex items-center gap-4">
                                            <img
                                                src={item.product?.image || "https://via.placeholder.com/60"}
                                                alt={item.product?.name}
                                                className="w-16 h-16 object-cover rounded-lg bg-[#fcece9]"
                                            />
                                            <div className="flex-1">
                                                <h4 className="font-medium text-[#333333]">{item.product?.name}</h4>
                                                <p className="text-sm text-[#666666]">Qty: {item.quantity} x ₱{parseFloat(item.price).toFixed(2)}</p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Cancellation Modal */}
            {cancelModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                    <div className="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                        <h3 className="text-xl font-bold text-[#333333] mb-4">Cancel Order</h3>
                        <p className="text-[#666666] mb-4">Please select a reason for cancelling your order:</p>

                        <div className="space-y-3 mb-4">
                            {cancelReasons.map((reason) => (
                                <div key={reason} className="flex items-center">
                                    <input
                                        type="radio"
                                        id={reason}
                                        name="cancelReason"
                                        value={reason}
                                        checked={cancelReason === reason}
                                        onChange={(e) => setCancelReason(e.target.value)}
                                        className="h-4 w-4 text-[#e76f51] focus:ring-[#e76f51] border-gray-300"
                                    />
                                    <label htmlFor={reason} className="ml-3 block text-sm font-medium text-[#333333]">
                                        {reason}
                                    </label>
                                </div>
                            ))}
                        </div>

                        {cancelReason === "Other" && (
                            <textarea
                                className="w-full border border-gray-300 rounded-lg p-2 mb-4 focus:ring-[#e76f51] focus:border-[#e76f51]"
                                placeholder="Please specify your reason..."
                                value={customReason}
                                onChange={(e) => setCustomReason(e.target.value)}
                            />
                        )}

                        <div className="flex justify-end gap-3">
                            <button
                                onClick={() => setCancelModalOpen(false)}
                                className="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium"
                            >
                                Keep Order
                            </button>
                            <button
                                onClick={handleCancelOrder}
                                className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
                            >
                                Confirm Cancellation
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
