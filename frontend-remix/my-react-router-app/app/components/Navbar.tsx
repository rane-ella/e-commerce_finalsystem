import { Link, Form } from "react-router";
import { useState, useEffect } from "react";

export default function Navbar() {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [isProfileOpen, setIsProfileOpen] = useState(false);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [userName, setUserName] = useState("");
    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            setIsLoggedIn(true);
            // Fetch user profile
            fetch('http://localhost:8000/api/user', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.name) setUserName(data.name);
                    if (data.role === 'admin') setIsAdmin(true);
                })
                .catch(err => console.error("Failed to fetch user", err));
        }
    }, []);

    const handleLogout = () => {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    };

    return (
        <nav className="bg-white shadow-sm sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between h-20">
                    <div className="flex items-center">
                        <Link to="/dashboard" className="flex-shrink-0 flex items-center">
                            <img className="h-12 w-auto rounded-full" src="/images/hero-logo.jpg" alt="GlowBabe" />
                            <span className="ml-3 font-serif text-2xl font-bold text-[#e76f51]">GlowBabe</span>
                        </Link>
                        <div className="hidden sm:ml-10 sm:flex sm:space-x-8">
                            <Link to="/dashboard" className="border-transparent text-gray-500 hover:border-[#e76f51] hover:text-[#e76f51] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Home
                            </Link>
                            <Link to="/products" className="border-transparent text-gray-500 hover:border-[#e76f51] hover:text-[#e76f51] inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Shop
                            </Link>
                        </div>
                    </div>
                    <div className="hidden sm:ml-6 sm:flex sm:items-center space-x-6">
                        <Form action="/products" method="get" className="relative">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search..."
                                className="bg-gray-100 rounded-full py-2 px-4 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-[#e76f51] w-64"
                            />
                            <button type="submit" className="absolute left-3 top-2.5">
                                <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </Form>

                        {!isAdmin && (
                            <Link to="/cart" className="text-gray-400 hover:text-[#e76f51] relative">
                                <span className="sr-only">Cart</span>
                                <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </Link>
                        )}

                        {isLoggedIn ? (
                            <div className="relative ml-3">
                                <div>
                                    <button
                                        onClick={() => setIsProfileOpen(!isProfileOpen)}
                                        className="flex items-center max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e76f51]"
                                        id="user-menu-button"
                                        aria-expanded="false"
                                        aria-haspopup="true"
                                    >
                                        <span className="sr-only">Open user menu</span>
                                        <div className="h-8 w-8 rounded-full bg-[#fcece9] flex items-center justify-center text-[#e76f51] font-bold">
                                            {userName.charAt(0).toUpperCase()}
                                        </div>
                                    </button>
                                </div>
                                {isProfileOpen && (
                                    <div
                                        className="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                        role="menu"
                                        aria-orientation="vertical"
                                        aria-labelledby="user-menu-button"
                                        tabIndex={-1}
                                    >
                                        <div className="px-4 py-2 border-b border-gray-100">
                                            <p className="text-sm text-[#333333] font-medium truncate">{userName}</p>
                                        </div>
                                        {isAdmin ? (
                                            <>
                                                <Link
                                                    to="/admin/dashboard"
                                                    className="block px-4 py-2 text-sm text-[#e76f51] font-bold hover:bg-gray-100"
                                                    role="menuitem"
                                                    tabIndex={-1}
                                                    onClick={() => setIsProfileOpen(false)}
                                                >
                                                    Admin Dashboard
                                                </Link>
                                                <Link
                                                    to="/admin/orders"
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    role="menuitem"
                                                    tabIndex={-1}
                                                    onClick={() => setIsProfileOpen(false)}
                                                >
                                                    Manage Orders
                                                </Link>
                                                <Link
                                                    to="/admin/products"
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    role="menuitem"
                                                    tabIndex={-1}
                                                    onClick={() => setIsProfileOpen(false)}
                                                >
                                                    Manage Products
                                                </Link>
                                            </>
                                        ) : (
                                            <Link
                                                to="/orders"
                                                className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                role="menuitem"
                                                tabIndex={-1}
                                                onClick={() => setIsProfileOpen(false)}
                                            >
                                                My Purchases
                                            </Link>
                                        )}
                                        <button
                                            onClick={() => {
                                                setIsProfileOpen(false);
                                                handleLogout();
                                            }}
                                            className="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem"
                                            tabIndex={-1}
                                        >
                                            Logout
                                        </button>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <Link to="/login" className="text-gray-400 hover:text-[#e76f51]">
                                <span className="sr-only">Login</span>
                                <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </Link>
                        )}
                    </div>
                    <div className="-mr-2 flex items-center sm:hidden">
                        <button
                            onClick={() => setIsMenuOpen(!isMenuOpen)}
                            className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#e76f51]"
                        >
                            <span className="sr-only">Open main menu</span>
                            {isMenuOpen ? (
                                <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            ) : (
                                <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            )}
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile menu */}
            {isMenuOpen && (
                <div className="sm:hidden">
                    <div className="pt-2 pb-3 space-y-1">
                        <Link to="/dashboard" className="bg-[#fcece9] border-[#e76f51] text-[#e76f51] block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Home
                        </Link>
                        <Link to="/products" className="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Shop
                        </Link>
                        {!isAdmin && (
                            <Link to="/cart" className="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                Cart
                            </Link>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
