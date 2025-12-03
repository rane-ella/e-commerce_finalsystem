import { Link, useNavigation, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export default function Cart() {
    const [cartItems, setCartItems] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");
    const navigation = useNavigation();
    const navigate = useNavigate();
    const isSubmitting = navigation.state === "submitting";

    useEffect(() => {
        fetchCart();
    }, []);

    const fetchCart = async () => {
        try {
            const token = localStorage.getItem('auth_token');
            console.log("Cart Fetch - Token:", token);

            if (!token) {
                console.warn("Cart Fetch - No token found in localStorage");
                setLoading(false);
                return;
            }

            console.log("Cart Fetch - Sending request to /cart...");
            const response = await api.get("/cart", {
                headers: { Authorization: `Bearer ${token}` }
            });

            console.log("Cart Fetch - Response Status:", response.status);
            console.log("Cart Fetch - Response Data:", response.data);

            setCartItems(response.data || []);
        } catch (err: any) {
            console.error("Cart Fetch - Failed:", err);
            console.error("Cart Fetch - Error Response:", err.response);
            setError("Failed to load cart items.");
        } finally {
            setLoading(false);
        }
    };

    const handleRemove = async (itemId: string) => {
        if (!window.confirm("Are you sure you want to remove this item from your cart?")) {
            return;
        }
        try {
            const token = localStorage.getItem('auth_token');
            await api.delete(`/cart/remove/${itemId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            // Refresh cart
            fetchCart();
        } catch (err) {
            console.error("Failed to remove item", err);
            alert("Failed to remove item.");
        }
    };

    const subtotal = cartItems.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);

    if (loading) {
        return (
            <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
                <Navbar />
                <div className="flex justify-center items-center h-64">
                    <p className="text-xl text-[#666666]">Loading cart...</p>
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

                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8">Shopping Cart</h1>

                {cartItems.length === 0 ? (
                    <div className="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <p className="text-xl text-[#666666] mb-6">Your cart is empty</p>
                        <Link
                            to="/products"
                            className="inline-block px-8 py-3 bg-[#e76f51] text-white font-bold rounded-xl hover:bg-[#d15a3a] transition-colors"
                        >
                            Start Shopping
                        </Link>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div className="md:col-span-2 space-y-4">
                            {cartItems.map((item) => (
                                <div key={item.id} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-6">
                                    <img
                                        src={item.product.image || "https://via.placeholder.com/150"}
                                        alt={item.product.name}
                                        className="w-24 h-24 object-cover rounded-lg bg-[#fcece9]"
                                    />
                                    <div className="flex-1">
                                        <h3 className="text-lg font-medium text-[#333333]">{item.product.name}</h3>
                                        <p className="text-[#e76f51] font-bold mt-1">₱{parseFloat(item.product.price).toFixed(2)}</p>
                                        <div className="flex items-center gap-4 mt-4">
                                            <span className="text-[#666666]">Qty: {item.quantity}</span>
                                            <button
                                                onClick={() => handleRemove(item.id)}
                                                disabled={isSubmitting}
                                                className="text-red-500 hover:text-red-700 text-sm font-medium transition-colors"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="md:col-span-1">
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                                <h2 className="text-xl font-serif font-bold mb-4 text-[#333333]">Order Summary</h2>
                                <div className="flex justify-between text-lg font-bold text-[#333333] mb-6">
                                    <span>Subtotal</span>
                                    <span>₱{subtotal.toFixed(2)}</span>
                                </div>
                                <Link
                                    to="/checkout"
                                    className="block w-full py-4 text-center bg-[#e76f51] text-white font-bold rounded-xl hover:bg-[#d15a3a] transition-colors shadow-lg hover:shadow-xl uppercase tracking-wide"
                                >
                                    Proceed to Checkout
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
