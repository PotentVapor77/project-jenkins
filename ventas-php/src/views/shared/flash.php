<?php 
// Asegúrate de incluir el SessionService si no está en un cargador automático
namespace Vendor\VentasPhp\Services;
use Vendor\VentasPhp\Services\SessionService;

// Verificar si hay mensajes flash para mostrar
if (SessionService::hasFlash()): 
    $flash = SessionService::getFlash(); 
?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show mt-3">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>