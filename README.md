📌 Overview

This project is a client–server implementation of the Connect Four game.

The server is written in PHP and provides REST-style endpoints for game logic and AI strategies.

The client is a Java application (c4Client.jar) that communicates with the PHP service to play the game.

The project demonstrates object-oriented design, client–server communication, and the Strategy design pattern.

🧠 Features
Server (PHP)

Full Connect Four game logic

REST-style API

Strategy pattern for move selection

Random strategy

Smart (rule-based) strategy

Modular and extensible design

Client (Java)

Command-line Java client

Connects to the PHP server via HTTP

Sends moves and receives game state updates

Supports different AI strategies

--------------------🗂 Project Structure--------------------
C4Project/
├── C4Service/                 # PHP server
│   ├── src/
│   │   ├── info/
│   │   │   └── index.php
│   │   ├── new/
│   │   │   └── index.php
│   │   └── play/
│   │       ├── index.php
│   │       ├── Game.php
│   │       ├── Board.php
│   │       ├── MoveStrategy.php
│   │       ├── RandomStrategy.php
│   │       └── SmartStrategy.php
│   └── .project
│
├── c4Client.jar               # Java client
└── README.md


⚙️ Requirements
Server

PHP 7.4+

Local web server:

XAMPP / MAMP / WAMP

OR PHP built-in server

Client

Java JDK 8+

Terminal or command prompt

--------------------🚀 Running the Server--------------------
Option 1: PHP Built-in Server

From the C4Service directory:
php -S localhost:8000

Test the server:
http://localhost:8000/src/info

--------------------Option 2: XAMPP / MAMP--------------------

Place C4Service inside htdocs

Start Apache

Open:
http://localhost/C4Service/src/info

▶️ Running the Client

Make sure the server is running first.

From the directory containing c4Client.jar:

java -jar c4Client.jar

The client will connect to the PHP server and allow gameplay through the terminal.

⚠️ Ensure the server URL inside the client configuration matches where your PHP service is hosted (e.g., localhost:8000).

🌐 API Endpoints

| Endpoint    | Description                            |
| ----------- | -------------------------------------- |
| `/src/info` | Service status and information         |
| `/src/new`  | Starts a new game                      |
| `/src/play` | Plays a move using a selected strategy |

🧩 AI Strategies

RandomStrategy

Selects a random valid column

SmartStrategy

Uses simple heuristics to choose stronger moves

New strategies can be added by extending the abstract MoveStrategy class.

🛠 Extending the Project

Add new AI strategies on the server

Improve the Java client UI

Add win prediction or difficulty levels

Create a web or GUI client

📚 Concepts Demonstrated

Client–Server Architecture

RESTful Services

Object-Oriented Programming (OOP)

Strategy Design Pattern

PHP backend development

Java client integration

👤 Author

Diego Villanueva

📄 License

This project is intended for educational use.
You are free to modify and extend it.


