import { useLoaderData, Link, useNavigate } from "react-router";
import api from "~/services/api";
import Navbar from "~/components/Navbar";

export async function loader({ request }: { request: Request }) {
    try {
        const response = await api.get("/products");
        return { products: response.data.data || response.data };
    } catch (error) {
        console.error("Failed to fetch products", error);
        return { products: [] };
    }
}

export default function Dashboard() {
    const { products } = useLoaderData<{ products: any[] }>();
    const navigate = useNavigate();

    // Limit to 4 bestsellers
    const bestsellers = products.slice(0, 4);

    return (
        <div className="font-sans text-[#333333] bg-[#fcfbf8] min-h-screen">
            <Navbar />

            {/* Hero Section */}
            <section className="text-center py-12 px-4 bg-[#fcfbf8]">
                <div className="max-w-6xl mx-auto">
                    <h2 className="text-4xl md:text-5xl font-serif text-[#e76f51] mb-8 font-bold">Welcome to GlowBabe</h2>
                    <img src="/images/hero-logo.jpg" alt="GlowBabe Logo" className="mx-auto mb-8 max-w-[400px] w-full h-auto rounded-lg shadow-sm" />
                    <p className="font-sans text-xl max-w-2xl mx-auto mb-8 text-[#666666] leading-relaxed">
                        Discover your natural radiance with our premium collection of cosmetics and skincare. Designed to enhance your beauty, naturally.
                    </p>
                </div>
            </section>

            {/* Categories Section */}
            <section className="py-12 px-4">
                <div className="max-w-6xl mx-auto">
                    <h2 className="font-serif text-4xl text-center text-[#e76f51] mb-10">Shop By Category</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {[
                            { name: 'Lips', img: '/images/lips-category.png', slug: 'lips' },
                            { name: 'Face', img: 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=800&auto=format&fit=crop', slug: 'face' },
                            { name: 'Skincare', img: '/images/skincare-category.png', slug: 'skincare' },
                            { name: 'Eyes', img: 'https://i.pinimg.com/1200x/c2/a7/21/c2a721becb791de43aabf5ad8415b074.jpg', slug: 'eyes' }
                        ].map((cat) => (
                            <Link key={cat.slug} to={`/categories/${cat.slug}`} className="group block text-center rounded-lg overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-lg">
                                <div className="h-64 bg-[#e0e0e0] flex items-center justify-center overflow-hidden">
                                    <img src={cat.img} alt={cat.name} className="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                </div>
                                <h3 className="my-4 text-xl font-medium text-[#333333]">{cat.name}</h3>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {/* Bestsellers Section */}
            <section className="py-12 px-4 bg-white">
                <div className="max-w-6xl mx-auto">
                    <h2 className="font-serif text-4xl text-center text-[#e76f51] mb-10">GlowBabe Best Sellers</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {bestsellers.map((product) => (
                            <Link key={product.id} to={`/products/${product.id}`} className="block text-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <div className="h-48 bg-[#fcece9] mb-4 rounded flex items-center justify-center overflow-hidden">
                                    {(() => {
                                        console.log(`Product ${product.id} image:`, product.image);
                                        return product.image ? (
                                            <img src={product.image} alt={product.name} className="w-full h-full object-cover" onError={(e) => console.error(`Failed to load image for ${product.name}:`, e.currentTarget.src)} />
                                        ) : (
                                            <img src="/images/soft-matte-lip-cream.png" alt={product.name} className="w-full h-full object-cover" />
                                        );
                                    })()}
                                </div>
                                <div className="text-yellow-400 text-sm mb-2">⭐⭐⭐⭐⭐</div>
                                <h4 className="text-lg font-medium text-[#333333] mb-1">{product.name}</h4>
                                <p className="font-bold text-[#e76f51]">₱{Number(product.price).toLocaleString()}</p>
                            </Link>
                        ))}
                    </div>
                    <div className="text-center mt-10">
                        <Link to="/products" className="inline-block py-3 px-8 bg-[#555555] text-white font-bold rounded hover:bg-[#333333] transition-colors uppercase tracking-wide">
                            View All Products
                        </Link>
                    </div>
                </div>
            </section>

            <footer className="py-12 mt-16 bg-[#f7e7e3] text-center text-sm text-[#666666]">
                <p>&copy; 2025 GlowBabe. All Rights Reserved.</p>
            </footer>
        </div>
    );
}
