# cryptonite
PHP peer-to-peer end-to-end OpenSSL encrypted stealth messager
meant to be used in controlled environments with a strict firewall blocking all ports exept for port 80.

## features
- Uncracked OpenSSL algorithm for encrypting messages (the request is still sent over HTTP to bypass the port blocker)
- Decrypted messages are only transmitted over invisible local connections and never saved
- Simple, cross platform and structureless system, no setup required
- Peer-to-peer update and certificate generation
- Client mode for local chat sharing
- Safe user management with hashed password and account reset
- Exploit safe
- Font Awesome icons support (use icon:(name of icon))

## installation
Just copy the "cryptonite" folder into your server root, then browse to localhost/cryptonite and you are good to go. The wizard will guide you through the next steps.

## cross platform
Basicly, cryptonite works on every system capable of running a PHP enabled server.
However, OpenSSL on Windows is often misconfigured and needs to be fixed in order to generate valid private and public keys.
You can generate a X.509 private key (cert.key) and a corresponding public one (cert.public) yourself, or use the external certificate generation feature of cryptonite over a trusted HTTPS cryptonite server if using Windows.

### how it works
- When a press on the send button happens, the (yet uncrypted) data is sent to localhost, where a PHP script executes
- 1. Gets sharekey.public from the target
- 2. Encrypts message with the public key and builds request
- 3. Sends request over HTTP to target
- 4. Target index.php triggered by the request saves the string
- 5. frame.php decrypts the data with the private key and displays it (over localhost)

### future plans
- Full Windows support
- Secure external certificate generation (pw protected zip)
- File encryption support
- PGP support
