
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #dc2626;
            --secondary-gray: #4b5563;
            --accent-wine: #7c2d12;
            --dark-bg: #111827;
            --light-gray: #f3f4f6;
            --code-green: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--secondary-gray) 100%);
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--accent-wine) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            font-family: 'Fira Code', monospace;
        }

        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: var(--light-gray);
        }

        .code-block {
            background: rgba(17, 24, 39, 0.8);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem auto;
            max-width: 500px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .code-line {
            font-family: 'Fira Code', monospace;
            margin-bottom: 0.5rem;
        }

        .comment { color: #64748b; }
        .keyword { color: #f59e0b; }
        .function { color: #06b6d4; }
        .string { color: #10b981; }

        .back-btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--accent-wine) 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: transform 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            color: white;
        }

        .back-btn i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">¡Oops! Página no encontrada</div>
        
        <div class="code-block">
            <div class="code-line">
                <span class="comment">// Error 404: Página no encontrada</span>
            </div>
            <div class="code-line">
                <span class="keyword">try</span> {
            </div>
            <div class="code-line">
                &nbsp;&nbsp;<span class="function">findPage</span>(<span class="string">"página_solicitada"</span>);
            </div>
            <div class="code-line">
            } <span class="keyword">catch</span> (<span class="function">PageNotFoundException</span> $e) {
            </div>
            <div class="code-line">
                &nbsp;&nbsp;<span class="function">console</span>.<span class="function">error</span>(<span class="string">"Error 404: Ruta no encontrada"</span>);
            </div>
            <div class="code-line">}
            </div>
        </div>
        
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i>Volver atrás
        </a>
    </div>
</body>
</html>