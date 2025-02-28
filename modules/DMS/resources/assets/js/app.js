// HTML Elements

let peerConnections = {}; // เก็บ PeerConnection สำหรับแต่ละ device
let deviceLogs = {};
let selectedDeviceCall = null;
let selectedDeviceData = null;
let lastDeviceData = null;
let localStream = null;

let logs = []; // เก็บข้อความ Log


const configuration = {
    iceServers: [
        {
		urls: 'stun:stun.l.google.com:19302'
		},
        {
            urls: "turn:global.relay.metered.ca:80",
            username: "8e4444596d38a139d5a52f01",
            credential: "i7zVRJ8n+sjYURtX",
        },
        {
            urls: "turn:global.relay.metered.ca:80?transport=tcp",
            username: "8e4444596d38a139d5a52f01",
            credential: "i7zVRJ8n+sjYURtX",
        },
        {
            urls: "turn:global.relay.metered.ca:443",
            username: "8e4444596d38a139d5a52f01",
            credential: "i7zVRJ8n+sjYURtX",
        },
        {
            urls: "turns:global.relay.metered.ca:443?transport=tcp",
            username: "8e4444596d38a139d5a52f01",
            credential: "i7zVRJ8n+sjYURtX",
        }
    ]
};


// Handle incoming signals
socket.on('signal', async (data) => {
    const source = data.source;
    const signal = data.data;
    console.log('signal:', signal)
    if (!peerConnections[source]) {
        // สร้าง PeerConnection ถ้ายังไม่มี
        const peerConnection = new RTCPeerConnection(configuration);
        peerConnections[source] = peerConnection;

        // Handle ICE candidates
        peerConnection.onicecandidate = event => {
            if (event.candidate) {
                socket.emit('signal', {
                    target: source,
                    data: { candidate: event.candidate }
                });
            }
        };

        // Handle incoming tracks
        peerConnection.ontrack = event => {
            console.log("in");
            if (event.track.kind === 'audio') {
                const audio = new Audio();
                audio.srcObject = event.streams[0];
                audio.play();
            }
        };

        peerConnection.onconnectionstatechange = () => {
            console.log('ICE connection state:', peerConnection.iceConnectionState);
            console.log(`Connection state changed: ${peerConnection.connectionState}`);
            switch (peerConnection.connectionState) {
                case "connected":
                    console.log('In Call');
                    break;
                case "disconnected":
                    console.log('disconnected');
                    break;
                case "failed":
                    console.log('faied');
                    break;
                case "closed":
                    console.log('Ready to calls');
                    delete peerConnections[source];
                    if (localStream) {
                        localStream.getTracks().forEach(track => track.stop());
                        localStream = null;
                    }
                    break;
            }
        };
    }

    const peerConnection = peerConnections[source];

    if (signal.sdp) {
        try {
            const remoteDesc = new RTCSessionDescription(signal);
            await peerConnection.setRemoteDescription(remoteDesc);
            if (signal.type === 'offer') {
                // สร้าง Answer
                const answer = await peerConnection.createAnswer();
                await peerConnection.setLocalDescription(answer);
                socket.emit('signal', {
                    target: source,
                    data: { sdp: peerConnection.localDescription }
                });
            }

            // แสดง SDP Answer ในหน้าเว็บ
            else if (signal.type === 'answer') {
                console.log("In Call");
            }
        } catch (err) {
            console.error('Error setting remote description:', err);
        }
    }

    if (signal.candidate) {
        try {
            await peerConnection.addIceCandidate(new RTCIceCandidate(signal.candidate));
        } catch (err) {
            console.error('Error adding received ice candidate', err);
        }
    }
});
