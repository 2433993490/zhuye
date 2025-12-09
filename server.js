const express = require('express');
const cors = require('cors');
const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args));
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// 1. 允许跨域 (CORS)
// 如果您的前端和后端在同一个域名下，可以更严格地限制 origin
app.use(cors());
app.use(express.json());

// 2. 健康检查接口
app.get('/', (req, res) => {
    res.send('AI Proxy Server is running!');
});

// 3. 核心聊天接口
app.post('/api/chat', async (req, res) => {
    try {
        const { messages } = req.body;
        
        // 从服务器环境变量获取 Key，绝对安全
        const API_KEY = process.env.API_KEY;
        const API_URL = process.env.API_URL || "https://api.deepseek.com/chat/completions";
        const MODEL_NAME = process.env.MODEL_NAME || "deepseek-chat";

        if (!API_KEY) {
            console.error('Error: API_KEY is missing in .env file');
            return res.status(500).json({ error: { message: "Server misconfiguration: API_KEY missing" } });
        }

        console.log(`[Request] Forwarding to ${API_URL}...`);

        // 向 AI 服务商发起请求
        const aiResponse = await fetch(API_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${API_KEY}`
            },
            body: JSON.stringify({
                model: MODEL_NAME,
                messages: messages,
                stream: false // 暂不使用流式，简化前端处理
            })
        });

        // 检查上游响应状态
        if (!aiResponse.ok) {
            const errorData = await aiResponse.text();
            console.error('[Upstream Error]', aiResponse.status, errorData);
            return res.status(aiResponse.status).json({ error: { message: `Upstream error: ${aiResponse.status}` } });
        }

        const data = await aiResponse.json();
        
        // 返回给前端
        res.json(data);

    } catch (error) {
        console.error('[Server Error]', error);
        res.status(500).json({ error: { message: error.message } });
    }
});

// 启动服务器
app.listen(PORT, () => {
    console.log(`==========================================`);
    console.log(`🚀 AI Proxy Server running on port ${PORT}`);
    console.log(`🔗 Endpoint: http://localhost:${PORT}/api/chat`);
    console.log(`==========================================`);
});
