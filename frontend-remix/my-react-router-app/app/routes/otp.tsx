import { Form, useActionData, useNavigation, useSearchParams, redirect } from "react-router";
import { useState, useEffect } from "react";
import api from "~/services/api";

export async function action({ request }: { request: Request }) {
    const formData = await request.formData();
    const email = formData.get("email");
    const otp = formData.get("otp");

    try {
        const response = await api.post("/login/otp/verify", { email, otp });
        const { access_token } = response.data;

        return { token: access_token };
    } catch (error: any) {
        return { error: error.response?.data?.message || "Invalid OTP" };
    }
}

export default function Otp() {
    const [searchParams] = useSearchParams();
    const email = searchParams.get("email");
    const actionData = useActionData<{ error?: string; token?: string }>();
    const navigation = useNavigation();
    const isSubmitting = navigation.state === "submitting";

    // Timer State
    const [timeLeft, setTimeLeft] = useState(300); // 5 minutes in seconds
    const [canResend, setCanResend] = useState(false);
    const [resendStatus, setResendStatus] = useState<"idle" | "sending" | "sent" | "error">("idle");

    useEffect(() => {
        if (timeLeft > 0) {
            const timerId = setTimeout(() => setTimeLeft(timeLeft - 1), 1000);
            return () => clearTimeout(timerId);
        } else {
            setCanResend(true);
        }
    }, [timeLeft]);

    const formatTime = (seconds: number) => {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    };

    const handleResend = async () => {
        if (!email) return;
        setResendStatus("sending");
        try {
            await api.post("/login/otp/send", { email });
            setResendStatus("sent");
            setTimeLeft(300); // Reset timer
            setCanResend(false);
            setTimeout(() => setResendStatus("idle"), 3000); // Clear success message after 3s
        } catch (error) {
            setResendStatus("error");
            setTimeout(() => setResendStatus("idle"), 3000);
        }
    };

    // Handle token storage and redirect
    if (actionData?.token && typeof window !== 'undefined') {
        localStorage.setItem('auth_token', actionData.token);
        window.location.href = '/dashboard'; // Force reload/redirect to dashboard
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-[#fcfbf8] p-4 font-sans text-[#333333]">
            <div className="bg-white border border-[#e0e0e0] rounded-2xl p-8 shadow-xl w-full max-w-md">
                <div className="text-center mb-8">
                    <img src="/images/hero-logo.jpg" alt="GlowBabe Logo" className="mx-auto mb-4 w-32 h-auto" />
                    <h1 className="text-3xl font-serif font-bold text-[#e76f51] mb-2">Verify OTP</h1>
                    <p className="text-[#666666]">Enter the code sent to {email}</p>
                </div>

                <Form method="post" className="space-y-6">
                    <input type="hidden" name="email" value={email || ''} />

                    <div>
                        <label htmlFor="otp" className="block text-sm font-medium text-[#333333] mb-2">
                            One-Time Password
                        </label>
                        <input
                            type="text"
                            name="otp"
                            id="otp"
                            required
                            maxLength={6}
                            className="w-full px-4 py-3 rounded-lg bg-white border border-[#e0e0e0] text-[#333333] placeholder-[#999999] text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-[#e76f51] transition-all"
                            placeholder="000000"
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
                        {isSubmitting ? "Verifying..." : "Verify & Login"}
                    </button>
                </Form>

                <div className="mt-6 text-center">
                    <p className="text-sm text-[#666666] mb-2">
                        {canResend ? "Did not receive the code?" : `Resend code in ${formatTime(timeLeft)}`}
                    </p>
                    {canResend && (
                        <button
                            type="button"
                            onClick={handleResend}
                            disabled={resendStatus === "sending"}
                            className="text-[#e76f51] font-semibold hover:text-[#d15a3a] transition-colors disabled:opacity-50"
                        >
                            {resendStatus === "sending" ? "Sending..." : "Resend OTP"}
                        </button>
                    )}
                    {resendStatus === "sent" && (
                        <p className="text-green-600 text-sm mt-2">OTP sent successfully!</p>
                    )}
                    {resendStatus === "error" && (
                        <p className="text-red-600 text-sm mt-2">Failed to send OTP. Try again.</p>
                    )}
                </div>
            </div>
        </div>
    );
}
