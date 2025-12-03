import { useLoaderData, Link, useNavigate, useSearchParams } from "react-router";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export async function loader({ request }: { request: Request }) {
    const url = new URL(request.url);
    const search = url.searchParams.get("search");

    try {
        const params = search ? { search } : {};
        const response = await api.get("/products", { params });
        return { products: response.data.data || response.data || [] };
    } catch (error) {
        return { products: [] };
    }
}

export default function Products() {
    const { products } = useLoaderData<{ products: any[] }>();
    const navigate = useNavigate();
    // Get search param from URL for display
    const [searchParams] = useSearchParams();
    const searchTerm = searchParams.get("search");

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

                <div className="text-center mb-12">
                    <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-4">
                        {searchTerm ? `Search Results for "${searchTerm}"` : "Discover Our Collection"}
                    </h1>
                    <p className="text-xl text-[#666666]">
                        {searchTerm ? `${products.length} result(s) found` : "Premium quality products curated just for you."}
                    </p>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    {products.map((product) => (
                        <Link
                            key={product.id}
                            to={`/products/${product.id}`}
                            className="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100"
                        >
                            <div className="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-[#fcece9] xl:aspect-w-7 xl:aspect-h-8 relative">
                                <img
                                    src={product.image || "https://via.placeholder.com/400"}
                                    alt={product.name}
                                    className={`h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300 ${product.stock <= 0 ? 'opacity-50 grayscale' : ''}`}
                                />
                                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors" />
                                {product.stock <= 0 && (
                                    <div className="absolute inset-0 flex items-center justify-center">
                                        <span className="bg-red-600 text-white px-4 py-2 font-bold uppercase tracking-wider text-sm shadow-lg transform -rotate-12">
                                            Out of Stock
                                        </span>
                                    </div>
                                )}
                            </div>
                            <div className="p-6">
                                <h3 className="mt-1 text-lg font-medium text-[#333333] group-hover:text-[#e76f51] transition-colors">
                                    {product.name}
                                </h3>
                                <p className="mt-1 text-sm text-[#666666] line-clamp-2">
                                    {product.description}
                                </p>
                                <div className="mt-4 flex items-center justify-between">
                                    <p className="text-xl font-bold text-[#e76f51]">
                                        ₱{parseFloat(product.price).toFixed(2)}
                                    </p>
                                    <span className="text-sm font-medium text-[#e76f51] bg-[#fcece9] px-3 py-1 rounded-full">
                                        View Details
                                    </span>
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </div >
    );
}
