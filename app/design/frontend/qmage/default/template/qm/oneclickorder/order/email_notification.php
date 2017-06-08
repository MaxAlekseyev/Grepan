<?php
    $_product = Mage::getModel('catalog/product')->load($this->getProductId());
?>
<table>
    <?php if ($this->getName()): ?>
    <tr>
        <td><?php echo $this->__('Name') ?></td>
        <td><?php echo $this->getName() ?></td>
    </tr>
    <?php endif ?>
    <tr>
        <td><?php echo $this->__('Telephone') ?></td>
        <td><?php echo $this->getTelephone() ?></td>
    </tr>
    <?php if ($_product->getId()): ?>
    <tr>
        <td><?php echo $this->__('Product') ?></td>
        <td><a href="<?php echo $_product->getProductUrl() ?>"><?php echo $_product->getName() ?></a></td>
    </tr>
    <?php endif ?>
</table>