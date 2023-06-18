import express from 'express';
import * as process from 'node:process'
import symfonyPlugin from "vite-plugin-symfony"

import { createServer as createViteServer } from 'vite'

async function createServer() {
    const port = 13714
    const hostName = '0.0.0.0'
    const app = express()

    const vite = await createViteServer({
        plugins: [
            symfonyPlugin()
        ],
        server: {
            port,
            middlewareMode: 'ssr'
        }
    })

    app.use(express.json())
    app.use(vite.middlewares)

    app.use('/health', (_, res) => res.send({
        status: 'OK',
        timestamp: Date.now()
    }))

    app.use('/404', (_, res) => res.send({
        status: 'NOT_FOUND',
        timestamp: Date.now()
    }))

    app.use('/shutdown', () => process.exit())

    app.post('/render', async (req, res) => {
        const { render } = await vite.ssrLoadModule('/assets/ssr.js')
        const response = await render(req.body)
        res.json(response)
    })

    app.listen(port, hostName)
    console.log(`Listening to Inertia requests on port ${port}`)
}

createServer()
