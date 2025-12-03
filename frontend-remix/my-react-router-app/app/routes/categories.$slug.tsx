import { useLoaderData, Link } from "react-router";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export async function loader({ params }: { params: { slug: string } }) {
    try {
        // Assuming there's an API endpoint to get products by category slug
        // If not, we might need to fetch all and filter, or fetch category first.
        // Let's try a direct call or a search.
        // Based on previous context, we might not have a direct 'products by category slug' endpoint ready.
        // Let's try fetching the category first to get its ID, then products.
        // Or simpler: GET /products?category_slug=slug

        const response = await api.get(`/products?category=${params.slug}`);
        return { products: response.data.data || response.data, slug: params.slug };
    } catch (error) {
        console.error("Failed to fetch category products", error);
        return { products: [], slug: params.slug };
    }
}

export default function CategoryPage() {
    const { products, slug } = useLoaderData<{ products: any[], slug: string }>();

    return (
        <div className="font-sans text-[#333333] bg-[#fcfbf8] min-h-screen">
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

                <h1 className="text-4xl font-serif font-bold text-[#e76f51] mb-8 capitalize text-center">
                    {slug} Collection
                </h1>

                {products.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {products.map((product) => (
                            <Link key={product.id} to={`/products/${product.id}`} className="block text-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <div className="h-48 bg-[#fcece9] mb-4 rounded flex items-center justify-center overflow-hidden">
                                    {product.image ? (
                                        <img src={product.image} alt={product.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <img src="/images/soft-matte-lip-cream.png" alt={product.name} className="w-full h-full object-cover" />
                                    )}
                                </div>
                                <div className="text-yellow-400 text-sm mb-2">⭐⭐⭐⭐⭐</div>
                                <h4 className="text-lg font-medium text-[#333333] mb-1">{product.name}</h4>
                                <p className="font-bold text-[#e76f51]">₱{Number(product.price).toLocaleString()}</p>
                            </Link>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-20">
                        <p className="text-xl text-[#666666]">No products found in this category.</p>
                        <Link to="/products" className="inline-block mt-4 text-[#e76f51] hover:underline">
                            Browse all products
                        </Link>
                    </div>
                )}
            </div>
        </div>
    );
}
