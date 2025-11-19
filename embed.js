/**
 * External Page Display - External Embed Loader v1.5
 * This file loads separately so updates don't require new embed codes
 */

(function() {
    'use strict';
    
    // Get parameters from script tag
    const scripts = document.getElementsByTagName('script');
    const currentScript = scripts[scripts.length - 1];
    const urlParams = new URLSearchParams(currentScript.src.split('?')[1]);
    
    const pageId = urlParams.get('page');
    const apiBase = decodeURIComponent(urlParams.get('api') || '');
    const version = urlParams.get('v') || '1.5';
    
    if (!pageId || !apiBase) {
        console.error('External Page Display: Missing required parameters');
        return;
    }
    
    const apiUrl = apiBase + pageId;
    
    // Add comprehensive WordPress/Gutenberg CSS
    const style = document.createElement('style');
    style.textContent = `
        /* Base Container Styles */
        #magazine-broadcast-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        
        /* Typography */
        #magazine-broadcast-content h1,
        #magazine-broadcast-content h2,
        #magazine-broadcast-content h3,
        #magazine-broadcast-content h4,
        #magazine-broadcast-content h5,
        #magazine-broadcast-content h6,
        #magazine-broadcast-content .source-title {
            text-align: center;
            margin-top: 1em;
            margin-bottom: 0.5em;
            font-weight: 600;
        }
        
        #magazine-broadcast-content h1 { font-size: 2.5em; }
        #magazine-broadcast-content h2 { font-size: 2em; }
        #magazine-broadcast-content h3 { font-size: 1.75em; }
        
        /* WordPress Block: Columns */
        #magazine-broadcast-content .wp-block-columns {
            display: flex;
            flex-wrap: wrap;
            gap: 2em;
            margin: 2.5em 0;
        }
        
        #magazine-broadcast-content .wp-block-column {
            flex: 1;
            min-width: 0;
            flex-basis: 0;
            flex-grow: 1;
        }
        
        @media (max-width: 782px) {
            #magazine-broadcast-content .wp-block-columns {
                flex-direction: column;
            }
            #magazine-broadcast-content .wp-block-column {
                flex-basis: 100%;
            }
        }
        
        /* WordPress Block: Buttons */
        #magazine-broadcast-content .wp-block-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5em;
            justify-content: center;
            margin: 1em 0;
        }
        
        #magazine-broadcast-content .wp-block-button {
            display: inline-block;
        }
        
        #magazine-broadcast-content .wp-block-button__link {
            background-color: #32373c;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-weight: 500;
            transition: background-color 0.2s ease;
            cursor: pointer;
            border: none;
        }
        
        #magazine-broadcast-content .wp-block-button__link:hover {
            background-color: #1e1e1e;
        }
        
        /* WordPress Block: Images */
        #magazine-broadcast-content .wp-block-image {
            margin: 1em 0;
            text-align: center;
        }
        
        #magazine-broadcast-content .wp-block-image img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        #magazine-broadcast-content .wp-block-image.aligncenter img {
            margin-left: auto;
            margin-right: auto;
        }
        
        #magazine-broadcast-content .wp-block-image.aligncenter {
            text-align: center;
        }
        
        #magazine-broadcast-content .wp-block-image figure {
            margin: 0;
        }
        
        /* WordPress Block: Group */
        #magazine-broadcast-content .wp-block-group {
            margin: 1em 0;
        }
        
        #magazine-broadcast-content .is-content-justification-center {
            justify-content: center;
        }
        
        #magazine-broadcast-content .is-nowrap {
            flex-wrap: nowrap;
        }
        
        /* Feature Image */
        #magazine-broadcast-content .feature-image {
            width: 100%;
            max-width: 1200px;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }
        
        #magazine-broadcast-content .source-article {
            width: 100%;
        }
        
        /* General Image Handling */
        #magazine-broadcast-content img {
            max-width: 100%;
            height: auto;
        }
        
        /* Content Spacing */
        #magazine-broadcast-content .source-content {
            margin: 0 auto;
            padding: 20px 0;
            max-width: 100%;
        }
        
        #magazine-broadcast-content p {
            margin: 1em 0;
        }
        
        /* Alignment Classes */
        #magazine-broadcast-content .alignwide {
            max-width: 100%;
        }
        
        #magazine-broadcast-content .aligncenter {
            text-align: center;
        }
        
        #magazine-broadcast-content .has-text-align-center {
            text-align: center;
        }
        
        /* Layout Helpers */
        #magazine-broadcast-content .is-layout-flex {
            display: flex;
        }
        
        #magazine-broadcast-content .is-layout-flow > * {
            margin-block-start: 0;
            margin-block-end: 0;
        }
        
        #magazine-broadcast-content .is-layout-flow > * + * {
            margin-block-start: 0.5em;
        }
        
        /* Handle CSS Variables */
        #magazine-broadcast-content [style*="--wp--preset--spacing"] {
            margin-top: 2.5em !important;
            margin-bottom: 2.5em !important;
        }
        
        /* Border Styles */
        #magazine-broadcast-content .has-border-color {
            border-style: solid;
        }
        
        #magazine-broadcast-content .has-contrast-border-color {
            border-color: #999;
        }
        
        /* Links */
        #magazine-broadcast-content a {
            color: #0073aa;
            text-decoration: none;
        }
        
        #magazine-broadcast-content a:hover {
            text-decoration: underline;
        }
        
        /* Loading State */
        #magazine-broadcast-content.loading {
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        
        /* Error State */
        #magazine-broadcast-content.error {
            padding: 20px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
        }
    `;
    document.head.appendChild(style);
    
    // Add loading state
    const container = document.getElementById('magazine-broadcast-content');
    if (!container) {
        console.error('External Page Display: Container element not found');
        return;
    }
    
    container.classList.add('loading');
    container.innerHTML = '<p>Loading content...</p>';
    
    // Fetch and display content
    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            container.classList.remove('loading');
            
            if (data && data.title && data.content) {
                const featureImage = data.feature_image ? 
                    `<img src="${data.feature_image}" alt="Feature Image" class="feature-image">` : "";
                
                container.innerHTML = `
                    <div class="source-article">
                        ${featureImage}
                        <h1 class="source-title">${data.title}</h1>
                        <div class="source-content">${data.content}</div>
                    </div>
                `;
                
                console.log('External Page Display: Content loaded successfully (v' + version + ')');
            } else {
                container.classList.add('error');
                container.innerHTML = "<p>No content found.</p>";
            }
        })
        .catch(error => {
            console.error('External Page Display Error:', error);
            container.classList.remove('loading');
            container.classList.add('error');
            container.innerHTML = "<p>Error loading content. Please try again later.</p>";
        });
})();
