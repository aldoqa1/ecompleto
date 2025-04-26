<div class="container">

    <?php include_once __DIR__ . "/../templates/alerts.php"; ?>

    <div class="row d-flex my-5 pb-5">

        <form class="validateForm" method="POST">    
            <h2 class="text-center fw-bold mb-3 mt-3">Validar pedido</h2>
            <label class="text-center w-100 m-auto d-block">Insira o número do pedido</label>

            <input type="text" name="orderNumber">
            <button>Enviar</button>
        </form>

    </div>

</div>
    