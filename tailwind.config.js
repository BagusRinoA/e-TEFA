export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.ts",
    ],
    theme: {
        extend: {
            colors: {
                background: "#f3f7f2",
                foreground: "#111827",
                primary: {
                    DEFAULT: "#2d7a3e",
                    foreground: "#ffffff",
                },
                secondary: {
                    DEFAULT: "#f0f7f2",
                    foreground: "#1f2937",
                },
                accent: {
                    DEFAULT: "#e8f5e9",
                    foreground: "#1f2937",
                },
                muted: {
                    DEFAULT: "#f5f5f0",
                    foreground: "#6b7280",
                },
                border: "rgba(45, 122, 62, 0.15)",
                destructive: "#dc2626",
            },
            keyframes: {
                fadeIn: {
                    from: {
                        opacity: "0",
                    },
                    to: {
                        opacity: "1",
                    },
                },
                fadeInUp: {
                    from: {
                        opacity: "0",
                        transform: "translateY(20px)",
                    },
                    to: {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                slideInDown: {
                    from: {
                        opacity: "0",
                        transform: "translateY(-10px)",
                    },
                    to: {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
            },
            animation: {
                fadeIn: "fadeIn 0.3s ease-in-out",
                fadeInUp: "fadeInUp 0.5s ease-out forwards",
                slideInDown: "slideInDown 0.3s ease-out",
            },
        },
    },
    plugins: [],
};
