<x-filament-panels::page>
    <style>
        .ai-terminal {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 12px;
            font-family: 'Courier New', Courier, monospace;
            padding: 15px;
            height: 150px;
            overflow-y: auto;
            color: #3fb950;
            font-size: 13px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .ai-terminal::-webkit-scrollbar {
            width: 4px;
        }

        .ai-terminal::-webkit-scrollbar-thumb {
            background: #3fb950;
            border-radius: 10px;
        }

        .pulse-red {
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }

            100% {
                opacity: 1;
            }
        }
    </style>

    <!-- 1. ترويسة حالة السيرفرات المتطورة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-2 h-10 bg-green-500 rounded-full shadow-[0_0_10px_#10b981]"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Emotion Engine</p>
                <h4 class="font-bold text-gray-800 dark:text-white text-sm">متصل (Online)</h4>
            </div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-2 h-10 bg-blue-500 rounded-full shadow-[0_0_10px_#3b82f6]"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">API Latency</p>
                <h4 class="font-bold text-gray-800 dark:text-white text-sm">45 ms <small
                        class="text-green-500">Fast</small></h4>
            </div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-2 h-10 bg-orange-500 rounded-full shadow-[0_0_10px_#f97316]"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">GPU Usage</p>
                <h4 class="font-bold text-gray-800 dark:text-white text-sm">24% Load</h4>
            </div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-2 h-10 bg-purple-500 rounded-full shadow-[0_0_10px_#a855f7]"></div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Database IO</p>
                <h4 class="font-bold text-gray-800 dark:text-white text-sm">2.4k req/m</h4>
            </div>
        </div>
    </div>

    <!-- 2. سحب الودجات (التركيز والنشاطات) هنا آلياً -->

    <!-- 3. نافذة التحكم واللوج الحركي (Terminal Console) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">

        <!-- التيرمينال الحركي -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-200">
            <h5 class="font-bold text-gray-700 dark:text-gray-200 mb-4 flex items-center gap-2">
                <i class="heroicon-o-command-line w-5 h-5"></i> سجل عمليات الذكاء الاصطناعي (Live Console)
            </h5>
            <div id="aiConsole" class="ai-terminal">
                <div>[INFO] System Booted...</div>
                <div>[INFO] Monitoring Emotion API signals...</div>
                <div>[LOG] Fetching data for Teacher ID: 1...</div>
            </div>
        </div>


    </div>

    <!-- Script لمحاكاة حركة التيرمينال المبهرة -->
    <script>
        const logs = [
            "[INFO] Neural Network successfully loaded weights...",
            "[API] Recommendation request for User ID: #302",
            "[AI] Confusion detected (Score: 78%) - Correcting path...",
            "[SUCCESS] Student Learning Profile #1240 updated.",
            "[SERVER] Health check PASSED",
            "[DATA] Extraction behavioral features... COMPLETE",
            "[INFO] Optimization cycle started..."
        ];

        let index = 0;
        const consoleEl = document.getElementById('aiConsole');

        setInterval(() => {
            const entry = document.createElement('div');
            entry.innerText = `> ${logs[index]}`;
            consoleEl.appendChild(entry);
            consoleEl.scrollTop = consoleEl.scrollHeight;
            index = (index + 1) % logs.length;
        }, 3000);
    </script>

</x-filament-panels::page>
