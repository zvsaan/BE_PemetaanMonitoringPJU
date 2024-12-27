const { default: makeWASocket, DisconnectReason, useSingleFileAuthState } = require('@whiskeysockets/baileys');
const { writeFileSync } = require('fs');
const sharp = require('sharp');
const { Boom } = require('@hapi/boom');
const { state, saveState } = useSingleFileAuthState('./auth_info.json');

const startBot = () => {
    const sock = makeWASocket({
        auth: state,
        printQRInTerminal: true,
    });

    sock.ev.on('creds.update', saveState);

    sock.ev.on('messages.upsert', async (msg) => {
        const message = msg.messages[0];
        if (!message.message || message.key.fromMe) return;

        const from = message.key.remoteJid;
        const text = message.message.conversation || message.message.extendedTextMessage?.text;

        if (text) {
            // Convert Text to Sticker
            const stickerBuffer = await sharp({
                create: {
                    width: 512,
                    height: 512,
                    channels: 3,
                    background: { r: 255, g: 255, b: 255 }
                }
            })
            .composite([{
                input: Buffer.from(`<svg><text x="10" y="100" font-size="60" fill="black">${text}</text></svg>`),
                top: 0,
                left: 0
            }])
            .png()
            .toBuffer();

            await sock.sendMessage(from, { sticker: stickerBuffer });
        }
    });

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect } = update;
        if (connection === 'close') {
            // if ((lastDisconnect.error as Boom)?.output?.statusCode !== DisconnectReason.loggedOut) {
                if (lastDisconnect && lastDisconnect.error && lastDisconnect.error.output && lastDisconnect.error.output.statusCode !== DisconnectReason.loggedOut) {

                startBot();
            } else {
                console.log('Connection closed. You are logged out.');
            }
        }
    });

    console.log('Bot is running...');
};


startBot();
