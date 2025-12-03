import { Link, useNavigate } from "react-router";
import { useState, useEffect } from "react";
import Navbar from "~/components/Navbar";
import api from "~/services/api";

export default function AdminProducts() {
    const navigate = useNavigate();
    const [products, setProducts] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchProducts();
    }, []);

    const fetchProducts = async () => {
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                navigate("/login");
                return;
            }

            const response = await api.get("/admin/products", {
                headers: { Authorization: `Bearer ${token}` }
            });
            setProducts(response.data || []);
        } catch (err) {
            console.error("Failed to fetch products", err);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id: number) => {
        if (!confirm("Are you sure you want to delete this product?")) return;

        try {
            const token = localStorage.getItem('auth_token');
            await api.delete(`/admin/products/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            setProducts(products.filter(p => p.id !== id));
            alert("Product deleted successfully.");
        } catch (err) {
            console.error("Failed to delete product", err);
            alert("Failed to delete product.");
        }
    };

    const handleOutOfStock = async (id: number) => {
        try {
            const token = localStorage.getItem('auth_token');
            await api.patch(`/admin/products/${id}/out-of-stock`, {}, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchProducts(); // Refresh to show updated stock
            alert("Product marked as out of stock.");
        } catch (err) {
            console.error("Failed to update stock", err);
            alert("Failed to update stock.");
        }
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
                <Navbar />
                <div className="flex justify-center items-center h-64">
                    <p className="text-xl text-[#666666]">Loading products...</p>
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

                <div className="flex justify-between items-center mb-8">
                    <h1 className="text-4xl font-serif font-bold text-[#e76f51]">Manage Products</h1>
                    <Link
                        to="/admin/products/new"
                        className="px-6 py-3 bg-[#e76f51] text-white font-bold rounded-xl hover:bg-[#d15a3a] transition-colors shadow-md"
                    >
                        + Add New Product
                    </Link>
                </div>

                <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="bg-gray-50">
                            <tr>
                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="bg-white divide-y divide-gray-200">
                            {products.map((product) => (
                                <tr key={product.id}>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="flex items-center">
                                            <div className="flex-shrink-0 h-10 w-10">
                                                <img
                                                    className="h-10 w-10 rounded-full object-cover bg-gray-100"
                                                    src={product.images?.[0]?.image_path ? `http://localhost:8000/storage/${product.images[0].image_path}` : "https://via.placeholder.com/40"}
                                                    alt=""
                                                    onError={(e) => {
                                                        (e.target as HTMLImageElement).src = "https://via.placeholder.com/40?text=No+Img";
                                                    }}
                                                />
                                            </div>
                                            <div className="ml-4">
                                                <div className="text-sm font-medium text-gray-900">{product.name}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-500">{product.category?.name || 'Uncategorized'}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <div className="text-sm text-gray-900">₱{parseFloat(product.price).toFixed(2)}</div>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${product.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                            {product.stock}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link to={`/admin/products/${product.id}`} className="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button onClick={() => handleOutOfStock(product.id)} className="text-orange-600 hover:text-orange-900 mr-4">Out of Stock</button>
                                        <button onClick={() => handleDelete(product.id)} className="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
