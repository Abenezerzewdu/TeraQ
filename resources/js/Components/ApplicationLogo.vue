<template>
    <svg 
        viewBox="0 0 100 100" 
        xmlns="http://www.w3.org/2000/svg"
        class="drop-shadow-[0_0_15px_rgba(37,99,235,0.3)]"
    >
        <!-- Background Circle/Orb -->
        <defs>
            <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
            </linearGradient>
        </defs>
        
        <!-- Styled 'Q' Shape -->
        <path 
            d="M50 15 C30 15, 15 30, 15 50 C15 70, 30 85, 50 85 C58 85, 65 82, 71 77 L85 91" 
            stroke="url(#logoGradient)" 
            stroke-width="12" 
            stroke-linecap="round" 
            fill="none" 
        />
        
        <!-- Kinetic 'T' Indicator -->
        <path 
            d="M35 50 L65 50 M50 35 L50 65" 
            stroke="white" 
            stroke-width="8" 
            stroke-linecap="round" 
            opacity="0.9"
        />
        
        <!-- Decorative Dot -->
        <circle cx="85" cy="91" r="6" fill="#60a5fa" />
    </svg>
</template>

