import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/createListing.css",
                "resources/css/error.css",
                "resources/css/FAQs.css",
                "resources/css/homepage.css",
                "resources/css/listAnItem.css",
                "resources/css/listings(old eto 'yung sa dating page).css",
                "resources/css/login.css",
                "resources/css/mainPage.css",
                "resources/css/myListings.css",
                "resources/css/myVoucher.css",
                "resources/css/orderPage.css",
                "resources/css/orgReport.css",
                "resources/css/productDetails.css",
                "resources/css/productDetails(old).css",
                "resources/css/productDetails(old1).css",
                "resources/css/profile.css",
                "resources/css/profile(old).css",
                "resources/css/profileSettings.css",
                "resources/css/review.css",
                "resources/css/select-role.css",
                "resources/css/viewListedItems.css",
                "resources/css/vouchers.css",
                "resources/css/wishlist.css",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',  // Allows access from other devices
        port: 5173,        // Keeps Vite on a fixed port
        strictPort: true,  // Ensures Vite doesn't change ports
        hmr: {
            host: 'localhost', // Replace this with your actual local IP
        }
    }
});
