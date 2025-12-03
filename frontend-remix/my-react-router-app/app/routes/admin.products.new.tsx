import { Link, useNavigate, Form } from "react-router";
import { useState, useEffect } from "react";
import Navbar from "~/components/Navbar";
import api from "~/services/api";

export default function AdminProductCreate() {
    const navigate = useNavigate();
    const [categories, setCategories] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    navigate("/login");
                    return;
                }

                const response = await api.get("/admin/products/create", {
                    headers: { Authorization: `Bearer ${token}` }
                });
                setCategories(response.data.categories || []);
            } catch (err) {
                console.error("Failed to fetch categories", err);
            } finally {
                setLoading(false);
            }
        };
        fetchCategories();
    }, []);

    const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        setSubmitting(true);

        const formData = new FormData(event.currentTarget);

        try {
            const token = localStorage.getItem('auth_token');
            await api.post("/admin/products", formData, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data'
                }
            });
            alert("Product created successfully!");
            navigate("/admin/products");
        } catch (err: any) {
            console.error("Failed to create product", err);
            alert("Failed to create product. " + (err.response?.data?.message || ""));
        } finally {
            setSubmitting(false);
        }
    };

    if (loading) return <div>Loading...</div>;

    return (
        <div className="min-h-screen bg-[#fcfbf8] font-sans text-[#333333]">
            <Navbar />
            <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className="flex items-center mb-8">
                    <button onClick={() => navigate(-1)} className="mr-4 text-gray-500 hover:text-[#e76f51]">
                        &larr; Back
                    </button>
                    <h1 className="text-3xl font-serif font-bold text-[#e76f51]">Add New Product</h1>
                </div>

                <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <form onSubmit={handleSubmit} className="space-y-6" encType="multipart/form-data">
                        <div>
                            <label htmlFor="name" className="block text-sm font-medium text-[#666666] mb-1">Product Name</label>
                            <input type="text" name="name" id="name" required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none" />
                        </div>

                        <div>
                            <label htmlFor="description" className="block text-sm font-medium text-[#666666] mb-1">Description</label>
                            <textarea name="description" id="description" rows={4} required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none"></textarea>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label htmlFor="price" className="block text-sm font-medium text-[#666666] mb-1">Price (₱)</label>
                                <input type="number" name="price" id="price" step="0.01" required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none" />
                            </div>
                            <div>
                                <label htmlFor="stock" className="block text-sm font-medium text-[#666666] mb-1">Stock Quantity</label>
                                <input type="number" name="stock" id="stock" required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none" />
                            </div>
                        </div>

                        <div>
                            <label htmlFor="category_id" className="block text-sm font-medium text-[#666666] mb-1">Category</label>
                            <select name="category_id" id="category_id" required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none">
                                <option value="">Select a category</option>
                                {categories
                                    .filter(cat => ['lips', 'face', 'skincare', 'eyes'].includes(cat.name.toLowerCase()))
                                    .map(cat => (
                                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                                    ))}
                            </select>
                        </div>

                        <div>
                            <label htmlFor="image" className="block text-sm font-medium text-[#666666] mb-1">Product Image</label>
                            <input type="file" name="image" id="image" accept="image/*" required className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#e76f51] focus:outline-none" />
                        </div>

                        <div className="pt-4">
                            <button
                                type="submit"
                                disabled={submitting}
                                className="w-full bg-[#e76f51] text-white font-bold py-3 px-4 rounded-xl hover:bg-[#d15a3a] transition-colors disabled:opacity-70"
                            >
                                {submitting ? "Creating..." : "Create Product"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
