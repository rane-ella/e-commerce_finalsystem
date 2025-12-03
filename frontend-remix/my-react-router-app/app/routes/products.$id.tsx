import { useLoaderData, Form, useNavigation, useActionData, redirect } from "react-router";
import { useState, useEffect } from "react";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export async function loader({ params }: { params: { id: string } }) {
    try {
        const response = await api.get(`/products/${params.id}`);
        return { product: response.data };
    } catch (error) {
        throw new Response("Not Found", { status: 404 });
    }
}

export async function action({ request, params }: { request: Request; params: { id: string } }) {
    const formData = await request.formData();
    const token = formData.get("token");
    const intent = formData.get("intent"); // 'add_to_cart' or 'buy_now'
    const quantity = parseInt(formData.get("quantity") as string) || 1;

    if (!token) {
        return { error: "Unauthenticated. Please login." };
    }

    try {
        await api.post(`/cart/add/${params.id}`, { quantity }, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        if (intent === 'buy_now') {
            return redirect("/checkout");
        }

        return { success: true };
    } catch (error: any) {
        return { error: error.response?.data?.message || "Failed to add to cart" };
    }
}

export default function ProductDetails() {
    const { product } = useLoaderData<{ product: any }>();
    const actionData = useActionData<{ success?: boolean; error?: string }>();
    const navigation = useNavigation();
    const isSubmitting = navigation.state === "submitting";
    const [token, setToken] = useState<string>("");
    const [quantity, setQuantity] = useState(1);
    const [isAdmin, setIsAdmin] = useState(false);
    const [isAuthChecking, setIsAuthChecking] = useState(true);

    useEffect(() => {
        const storedToken = localStorage.getItem('auth_token');
        if (storedToken) {
            setToken(storedToken);
            // Fetch user to check role
            api.get('/user', { headers: { Authorization: `Bearer ${storedToken}` } })
                .then(res => {
                    if (res.data.role === 'admin') setIsAdmin(true);
                })
                .catch(err => console.error(err))
                .finally(() => setIsAuthChecking(false));
        } else {
            setIsAuthChecking(false);
        }
    }, []);

    return (
        <div className="min-h-screen bg-white font-sans text-[#333333]">
            <Navbar />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <button
                    onClick={() => window.history.back()}
                    className="flex items-center text-[#666666] hover:text-[#e76f51] transition-colors mb-8"
                >
                    <svg className="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>
                <div className="lg:grid lg:grid-cols-2 lg:gap-x-16 lg:items-start">
                    {/* Image gallery */}
                    <div className="flex flex-col-reverse">
                        <div className="w-full aspect-w-1 aspect-h-1 rounded-2xl overflow-hidden bg-gray-100 shadow-lg">
                            <img
                                src={product.image || "https://via.placeholder.com/600"}
                                alt={product.name}
                                className="w-full h-full object-center object-cover"
                            />
                        </div>
                    </div>

                    {/* Product info */}
                    <div className="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                        <h1 className="text-4xl font-serif font-bold tracking-tight text-[#e76f51]">
                            {product.name}
                        </h1>

                        <div className="mt-6">
                            <h2 className="sr-only">Product information</h2>
                            <p className="text-3xl text-[#333333] font-bold">
                                ₱{parseFloat(product.price).toFixed(2)}
                            </p>
                            {/* Stock Display */}
                            <p className="mt-2 text-sm text-[#666666]">
                                Stock: <span className={`font-medium ${product.stock > 0 ? 'text-[#333333]' : 'text-red-600'}`}>
                                    {product.stock > 0 ? product.stock : 'Out of Stock'}
                                </span>
                            </p>
                        </div>

                        <div className="mt-6">
                            <h3 className="sr-only">Description</h3>
                            <div className="text-base text-[#666666] space-y-6 leading-relaxed">
                                <p>{product.description}</p>
                            </div>
                        </div>

                        <div className="mt-10">
                            {actionData?.success && (
                                <div className="mb-4 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                                    Product added to cart successfully!
                                </div>
                            )}
                            {actionData?.error && (
                                <div className="mb-4 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                                    {actionData.error}
                                </div>
                            )}

                            {isAuthChecking ? (
                                <div className="flex justify-center py-4">
                                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-[#e76f51]"></div>
                                </div>
                            ) : (
                                <>
                                    {!isAdmin && product.stock > 0 && (
                                        <Form method="post" className="flex flex-col gap-4">
                                            <input type="hidden" name="token" value={token} />

                                            {/* Quantity Selector */}
                                            <div className="flex items-center gap-4 mb-2">
                                                <span className="text-[#666666] font-medium">Quantity:</span>
                                                <div className="flex items-center border border-gray-300 rounded-lg">
                                                    <button
                                                        type="button"
                                                        onClick={() => setQuantity(Math.max(1, quantity - 1))}
                                                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-lg"
                                                        disabled={product.stock <= 0}
                                                    >
                                                        -
                                                    </button>
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value={quantity}
                                                        readOnly
                                                        className="w-12 text-center border-x border-gray-300 py-1 text-[#333333]"
                                                        max={product.stock}
                                                    />
                                                    <button
                                                        type="button"
                                                        onClick={() => setQuantity(Math.min(product.stock, quantity + 1))}
                                                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-lg"
                                                        disabled={product.stock <= 0 || quantity >= product.stock}
                                                    >
                                                        +
                                                    </button>
                                                </div>
                                                <span className="text-xs text-gray-500">Max: {product.stock}</span>
                                            </div>

                                            <div className="flex flex-col sm:flex-row gap-4">
                                                <button
                                                    type="submit"
                                                    name="intent"
                                                    value="add_to_cart"
                                                    disabled={isSubmitting || !token}
                                                    className="flex-1 bg-white border-2 border-[#e76f51] rounded-xl py-4 px-8 flex items-center justify-center text-lg font-bold text-[#e76f51] hover:bg-[#fcece9] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e76f51] transition-all shadow-sm hover:shadow-md disabled:opacity-70 disabled:cursor-not-allowed uppercase tracking-wide"
                                                >
                                                    {isSubmitting ? "Adding..." : "Add to Cart"}
                                                </button>

                                                <button
                                                    type="submit"
                                                    name="intent"
                                                    value="buy_now"
                                                    disabled={isSubmitting || !token}
                                                    className="flex-1 bg-[#e76f51] border-2 border-[#e76f51] rounded-xl py-4 px-8 flex items-center justify-center text-lg font-bold text-white hover:bg-[#d15a3a] hover:border-[#d15a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e76f51] transition-all shadow-lg hover:shadow-xl disabled:opacity-70 disabled:cursor-not-allowed uppercase tracking-wide"
                                                >
                                                    Buy Now
                                                </button>
                                            </div>
                                        </Form>
                                    )}
                                </>
                            )}

                            {!isAdmin && product.stock <= 0 && (
                                <div className="w-full bg-gray-100 border-2 border-gray-200 rounded-xl py-4 px-8 flex items-center justify-center text-lg font-bold text-gray-400 uppercase tracking-wide cursor-not-allowed">
                                    Out of Stock
                                </div>
                            )}

                            {!token && (
                                <p className="mt-4 text-sm text-center text-red-500">Please login to purchase items.</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
