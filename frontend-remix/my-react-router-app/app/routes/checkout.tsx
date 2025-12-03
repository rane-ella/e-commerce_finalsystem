import { useLoaderData, Form, useNavigation, useActionData, Link, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export default function Checkout() {
    const [cartItems, setCartItems] = useState<any[]>([]);
    const [user, setUser] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const [paymentMethod, setPaymentMethod] = useState("cod");
    const navigation = useNavigation();
    const navigate = useNavigate();
    const isSubmitting = navigation.state === "submitting";
    const [error, setError] = useState("");

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                navigate("/login");
                return;
            }

            const [cartRes, userRes] = await Promise.all([
                api.get("/cart", { headers: { Authorization: `Bearer ${token}` } }),
                api.get("/user", { headers: { Authorization: `Bearer ${token}` } })
            ]);

            setCartItems(cartRes.data || []);
            setUser(userRes.data);
        } catch (err) {
            console.error("Failed to load checkout data", err);
            setError("Failed to load checkout data. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        setError("");

        const formData = new FormData(event.currentTarget);
        const data = {
            address: formData.get("address"),
            phone: formData.get("phone"),
            notes: formData.get("notes"),
            payment_method: paymentMethod
        };

        try {
            const token = localStorage.getItem('auth_token');

            if (paymentMethod === 'card') {
                // PayMongo Checkout
                const response = await api.post("/payments/paymongo/checkout", {
                    amount: total,
                    address: data.address,
                    phone: data.phone,
                    notes: data.notes
                }, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                window.location.href = response.data.checkout_url;
            } else if (paymentMethod === 'paypal') {
                // PayPal Checkout
                const response = await api.post("/payments/paypal/create", { amount: total }, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                window.location.href = response.data.checkout_url;
            } else {
                // COD
                await api.post("/checkout", data, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                alert("Order placed successfully!");
                navigate("/dashboard"); // Or order success page
            }
        } catch (err: any) {
            console.error("Checkout failed", err);
            setError(err.response?.data?.message || "Checkout failed. Please try again.");
        }
    };

    const subtotal = cartItems.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
    const shipping = 50;
    const total = subtotal + shipping;

    if (loading) {
        return (
            <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
                <Navbar />
                <div className="flex justify-center items-center h-64">
                    <p className="text-xl text-[#666666]">Loading checkout...</p>
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

                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8">Checkout</h1>

                <div className="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                    <section className="lg:col-span-7">
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {/* Shipping Info */}
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <h2 className="text-xl font-serif font-bold text-[#333333] mb-4">Shipping Information</h2>
                                <div className="space-y-4">
                                    <div>
                                        <label className="block text-sm font-medium text-[#666666] mb-1">Full Name</label>
                                        <input type="text" value={user?.name} disabled className="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-[#333333]" />
                                    </div>
                                    <div>
                                        <label htmlFor="address" className="block text-sm font-medium text-[#666666] mb-1">Address</label>
                                        <input
                                            type="text"
                                            name="address"
                                            defaultValue={user?.address}
                                            required
                                            className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none"
                                            placeholder="Enter your delivery address"
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="phone" className="block text-sm font-medium text-[#666666] mb-1">Phone Number</label>
                                        <input
                                            type="text"
                                            name="phone"
                                            defaultValue={user?.phone}
                                            required
                                            className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none"
                                            placeholder="Enter your phone number"
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Payment Method */}
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <h2 className="text-xl font-serif font-bold text-[#333333] mb-4">Payment Method</h2>
                                <div className="space-y-4">
                                    <div className="flex items-center">
                                        <input
                                            id="cod"
                                            name="payment_method"
                                            type="radio"
                                            value="cod"
                                            checked={paymentMethod === "cod"}
                                            onChange={(e) => setPaymentMethod(e.target.value)}
                                            className="h-4 w-4 text-[#e76f51] focus:ring-[#e76f51] border-gray-300"
                                        />
                                        <label htmlFor="cod" className="ml-3 block text-sm font-medium text-[#333333]">Cash on Delivery (COD)</label>
                                    </div>
                                    <div className="flex items-center">
                                        <input
                                            id="card"
                                            name="payment_method"
                                            type="radio"
                                            value="card"
                                            checked={paymentMethod === "card"}
                                            onChange={(e) => setPaymentMethod(e.target.value)}
                                            className="h-4 w-4 text-[#e76f51] focus:ring-[#e76f51] border-gray-300"
                                        />
                                        <label htmlFor="card" className="ml-3 block text-sm font-medium text-[#333333]">Pay via PayMongo (Cards, PayPal, E-wallets)</label>
                                    </div>
                                </div>
                            </div>

                            {/* Notes */}
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <label htmlFor="notes" className="block text-sm font-medium text-[#333333] mb-2">Order Notes (Optional)</label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows={3}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none"
                                ></textarea>
                            </div>

                            {error && (
                                <div className="p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                                    {error}
                                </div>
                            )}

                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="w-full bg-[#e76f51] border border-transparent rounded-xl py-4 px-8 text-lg font-bold text-white hover:bg-[#d15a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e76f51] transition-all shadow-lg hover:shadow-xl disabled:opacity-70 disabled:cursor-not-allowed uppercase tracking-wide"
                            >
                                {isSubmitting ? "Processing..." : "Confirm Order"}
                            </button>
                        </form>
                    </section>

                    {/* Order Summary */}
                    <section className="mt-16 lg:mt-0 lg:col-span-5">
                        <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                            <h2 className="text-xl font-serif font-bold text-[#333333] mb-6">Order Summary</h2>
                            <ul className="divide-y divide-gray-100 mb-6">
                                {cartItems.map((item) => (
                                    <li key={item.id} className="flex py-4">
                                        <img
                                            src={item.product.image || "https://via.placeholder.com/150"}
                                            alt={item.product.name}
                                            className="h-16 w-16 rounded-lg object-cover bg-[#fcece9]"
                                        />
                                        <div className="ml-4 flex-1">
                                            <h3 className="text-sm font-medium text-[#333333]">{item.product.name}</h3>
                                            <p className="text-sm text-[#666666] mt-1">Qty {item.quantity}</p>
                                            <p className="text-sm font-bold text-[#e76f51] mt-1">₱{parseFloat(item.product.price).toFixed(2)}</p>
                                        </div>
                                    </li>
                                ))}
                            </ul>

                            <dl className="space-y-4 border-t border-gray-100 pt-6">
                                <div className="flex items-center justify-between">
                                    <dt className="text-sm text-[#666666]">Subtotal</dt>
                                    <dd className="text-sm font-medium text-[#333333]">₱{subtotal.toFixed(2)}</dd>
                                </div>
                                <div className="flex items-center justify-between">
                                    <dt className="text-sm text-[#666666]">Shipping</dt>
                                    <dd className="text-sm font-medium text-[#333333]">₱{shipping.toFixed(2)}</dd>
                                </div>
                                <div className="flex items-center justify-between border-t border-gray-100 pt-4">
                                    <dt className="text-base font-bold text-[#333333]">Order Total</dt>
                                    <dd className="text-base font-bold text-[#e76f51]">₱{total.toFixed(2)}</dd>
                                </div>
                            </dl>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    );
}
