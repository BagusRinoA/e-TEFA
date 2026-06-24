export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#2d7a3e',
        secondary: '#f0f7f2',
        accent: '#e8f5e9',
        muted: {
          DEFAULT: '#f5f5f0',
          foreground: '#6b7280',
        },
        border: 'rgba(45, 122, 62, 0.15)',
        destructive: '#dc2626',
      },
    },
  },
  plugins: [],
}
