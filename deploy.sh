#!/bin/bash

# NUJ Courier Management System - Deployment Script
echo "🚀 Starting deployment process..."

# Step 1: Install dependencies
echo "📦 Installing npm dependencies..."
npm install

# Step 2: Build assets for production
echo "🔨 Building assets for production..."
npm run build

# Step 3: Check if build was successful
if [ $? -eq 0 ]; then
    echo "✅ Assets built successfully!"
    echo "📁 Built assets location: public/build/"
    echo "📊 Asset sizes:"
    ls -lh public/build/assets/
    
    # Step 4: Show what to commit
    echo ""
    echo "📝 Next steps:"
    echo "1. Add built assets to git:"
    echo "   git add public/build/"
    echo ""
    echo "2. Commit the changes:"
    echo "   git commit -m 'Build assets for production deployment'"
    echo ""
    echo "3. Push to your repository:"
    echo "   git push origin main"
    echo ""
    echo "4. Deploy to production server"
    echo ""
    echo "🎉 Your production server will now have the built assets without needing to run npm run build!"
else
    echo "❌ Build failed! Please check the errors above."
    exit 1
fi 