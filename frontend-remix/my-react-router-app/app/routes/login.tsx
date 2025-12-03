import { Form, useActionData, useNavigation, redirect } from "react-router";
import api from "~/services/api";

export async function action({ request }: { request: Request }) {
    const formData = await request.formData();
    const email = formData.get("email");
    const password = formData.get("password");

    try {
        await api.post("/login/otp/send", { email, password });
        return redirect(`/otp?email=${encodeURIComponent(email as string)}`);
    } catch (error: any) {
        return { error: error.response?.data?.message || "Failed to send OTP" };
    }
}

export default function Login() {
    const actionData = useActionData<{ error?: string }>();
    const navigation = useNavigation();
    const isSubmitting = navigation.state === "submitting";

    return (
        <div className="min-h-screen flex items-center justify-center bg-[#fcfbf8] p-4 font-sans text-[#333333]">
            <div className="bg-white border border-[#e0e0e0] rounded-2xl p-8 shadow-xl w-full max-w-md">
                <div className="text-center mb-8">
                    <img src="/images/hero-logo.jpg" alt="GlowBabe Logo" className="mx-auto mb-4 w-32 h-auto" />
                    <h1 className="text-3xl font-serif font-bold text-[#e76f51] mb-2">Welcome Back</h1>
                    <p className="text-[#666666]">Sign in with your credentials to continue</p>
                </div>

                <Form method="post" className="space-y-6">
                    <div>
                        <label htmlFor="email" className="block text-sm font-medium text-[#333333] mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            required
                            className="w-full px-4 py-3 rounded-lg bg-white border border-[#e0e0e0] text-[#333333] placeholder-[#999999] focus:outline-none focus:ring-2 focus:ring-[#e76f51] transition-all"
                            placeholder="you@example.com"
                        />
                    </div>

                    <div>
                        <label htmlFor="password" className="block text-sm font-medium text-[#333333] mb-2">
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            className="w-full px-4 py-3 rounded-lg bg-white border border-[#e0e0e0] text-[#333333] placeholder-[#999999] focus:outline-none focus:ring-2 focus:ring-[#e76f51] transition-all"
                            placeholder="Enter your password"
                        />
                    </div>

                    {actionData?.error && (
                        <div className="p-3 rounded-lg bg-red-50 text-red-600 text-sm text-center border border-red-200">
                            {actionData.error}
                        </div>
                    )}

                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className="w-full py-3 px-4 bg-[#e76f51] text-white font-bold rounded-lg hover:bg-[#d15a3a] focus:outline-none focus:ring-2 focus:ring-[#e76f51] transition-colors disabled:opacity-70 disabled:cursor-not-allowed uppercase tracking-wide"
                    >
                        {isSubmitting ? "Verifying..." : "Continue with OTP"}
                    </button>
                </Form>
            </div>
        </div>
    );
}
