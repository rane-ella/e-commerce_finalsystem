import axios from 'axios';

const API_URL = 'http://localhost:8000/api';
// Replace with a valid token you can get from the browser's localStorage or by logging in via this script
const TOKEN = 'YOUR_AUTH_TOKEN_HERE';

async function debugApi() {
    try {
        console.log("--- Debugging Products ---");
        const productsRes = await axios.get(`${API_URL}/products`);
        const products = productsRes.data.data || productsRes.data;
        if (products.length > 0) {
            console.log("First Product Image:", products[0].image);
            console.log("First Product Image Path (Raw):", products[0].images?.[0]?.image_path);
        } else {
            console.log("No products found.");
        }

        console.log("\n--- Debugging Cart ---");
        if (TOKEN === 'YOUR_AUTH_TOKEN_HERE') {
            console.log("Skipping Cart debug: No token provided. Please paste a valid token in the script.");
        } else {
            const cartRes = await axios.get(`${API_URL}/cart`, {
                headers: { Authorization: `Bearer ${TOKEN}` }
            });
            console.log("Cart Items:", cartRes.data);
        }

    } catch (error) {
        console.error("API Error:", error.response?.data || error.message);
    }
}

// If you need to login to get a token:
async function loginAndDebug() {
    try {
        console.log("\n--- Logging In ---");
        // Use a known test user
        const loginRes = await axios.post(`${API_URL}/login`, {
            email: 'johnroben@gmail.com', // Assuming this user exists from screenshots
            password: 'password' // Replace with actual password if known, or use OTP flow if password disabled
        });

        // Note: If using OTP flow, this won't work directly. 
        // But for now, let's assume we can check public data first.

        // For OTP flow, we'd need to trigger OTP and verify. 
        // Let's stick to public product data first.
    } catch (e) {
        // console.error("Login failed:", e.message);
    }

    await debugApi();
}

loginAndDebug();
