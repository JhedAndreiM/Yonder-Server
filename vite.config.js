import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/AboutUS.css",
                "resources/css/accountPage.css",
                "resources/css/addToCart.css",
                "resources/css/admin-org.css",
                "resources/css/admin-younder.css",
                "resources/css/app.css",
                "resources/css/createListing.css",
                "resources/css/error.css",
                "resources/css/FAQs.css",
                "resources/css/homepage.css",
                "resources/css/listAnItem.css",
                "resources/css/login.css",
                "resources/css/mainPage.css",
                "resources/css/myListings.css",
                "resources/css/myVoucher.css",
                "resources/css/orderPage.css",
                "resources/css/orgReport.css",
                "resources/css/productDetails.css",
                "resources/css/profile.css",
                "resources/css/profileSettings.css",
                "resources/css/review.css",
                "resources/css/select-role.css",
                "resources/css/tradeOffer.css",
                "resources/css/tradeOffers.css",
                "resources/css/viewListedItems.css",
                "resources/css/vouchers.css",
                "resources/css/wishlist.css",
                "resources/js/app.js",
                "resources/js/bootstrap.js",
                "resources/js/createListing.js",
                "resources/js/editAnItem.js",
                "resources/js/FAQs.js",
                "resources/js/homepage.js",
                "resources/js/jquery.jscroll.js",
                "resources/js/listAnItem.js",
                "resources/js/mainPage.js",
                "resources/js/productDetails.js",
                "resources/js/org.dashboard.js",
                "resources/js/review.js",
                "resources/js/tradeOffer.js",
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
