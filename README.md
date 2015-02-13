# cryptonite
PHP peer-to-peer end-to-end OpenSSL encrypted stealth messager

## features
cryptonite is meant to be used in controlled environments with a strict firewall blocking all ports exept for port 80.

- Uncracked OpenSSL algorithm for encrypting messages (the request is still sent over HTTP to bypass the port blocker)
- Decrypted messages are only transmitted over invisible local connections and never saved
- Simple, cross platform and structureless system, no setup required
- Peer-to-peer update and certificate generation
- Client mode for local chat sharing
- Safe user management with hashed password and account reset
- Advanced exploit detection

## installation
Just copy the "cryptonite" folder into your server root, then browse to localhost/cryptonite and you are good to go. The wizard will guide you through the next steps.

## cross platform
Basicly, cryptonite works on every system capable of running a PHP enabled server.
However, OpenSSL on Windows is often misconfigured and needs to be fixed in order to generate valid private and public keys.
You can generate a X.509 private key (cert.key) and a corresponding public one (cert.public) yourself, or use the external certificate generation feature of cryptonite over a trusted HTTPS cryptonite server if using Windows.
