<?php
  /*
    30-7-2016

    Animate a variable of the harmonic function used to
    determine the curvature along a curve

    Tags: Canvas, animation, curve, curvature
   */
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Animate curvature</title>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/dat-gui/0.5.1/dat.gui.min.js"></script>
        <script src="canvas.js"></script>
        <script>

            var curvature;
            var π = Math.PI;

            window.onload = function() {
                Canvas.init( 'thecanvas' , 400, 400, {strokeStyle: "#003d44"} );
                Canvas.objectFit(document.querySelector('main'), 'contain'); 

                setupDatGui();

                animate();
            }

            function animate() {
                var dϕ = 0.035;

                function frame() {
                    requestAnimationFrame(frame);
                    Params.ϕ = (Params.ϕ + dϕ) % (2 * π);
                    draw();
                }

                frame();
            }

            var Params = {
                A: 13,
                ω: 0.43,
                ϕ: 0
            }

            var Harmonic = function(A, ω, ϕ) {
                return (function(x){
                    var t = x * 2 * π;
                    return A * Math.sin(ω * t + ϕ);
                });
            }

            function draw() {
                drawCurve(new Harmonic(Params.A, Params.ω, Params.ϕ));
            }

            function setupDatGui() {
                var gui = new dat.GUI({ autoPlace: false });

                var customContainer = document.querySelector('main');
                customContainer.appendChild(gui.domElement);

                // Hide controls until user opens them
                gui.close();

                var controller_A = gui.add(Params, 'A', 0, 100).step(0.01);
                var controller_w = gui.add(Params, 'ω', 0, 2).step(0.01);
                var controller_ϕ = gui.add(Params, 'ϕ', 0, 2 * π).step(0.01).listen();

                controller_A.onChange(function(value) { draw(); });        
                controller_w.onChange(function(value) { draw(); });        
                controller_ϕ.onChange(function(value) { draw(); });        
            }

            function drawCurve(curvature) {
                var n = 1000;
                var L = 300;
                var d = L/n; // length of arms
                var θ = π/2; // angle with x-axis of the first degment

                /**
                * Calculate coordinates of endpoints
                * 
                * Ieder segment staat onder een hoek met het vorige segment,
                * de cumulatieve hoek ten opzichte van de x-as 
                * heet tangential angle (of turning angle) θ
                */
                var p = [];
                p[0] = {x: 0, y: 0};

                for (var i = 1; i < n + 1; i++) {
                    θ += curvature(i/n)/n;
                    p[i] = {
                        x: p[i-1].x + d * Math.cos(θ), 
                        y: p[i-1].y + d * Math.sin(θ)
                    }
                };

                Canvas.clear();
                Canvas.path(p, {lineWidth: 12});
            }
        </script>
        <style type="text/css">
            body, html {
                height:100%;
            }

            body {
                margin: 0;
                background: #fbf7da;
                background: radial-gradient(ellipse at center, rgba(255,255,255,1) 0%,rgba(255,255,255,0) 150%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                overflow:hidden;
            }

            main {
                width: 100%;
                height: 100%;
                position: relative;
            }

            /**
             * Position dat.gui 
             */
            .dg.main {
                position: absolute;
                right: 0;
                top: 0;
            }
        </style>
</head>
<body>
    <main>
        <canvas id="thecanvas"></canvas>
    </main>
</body>
</html>