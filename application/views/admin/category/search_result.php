<?php foreach ( $products as $product) : ?>
<tr>
	<td><?= $product['id']?></td>
	<td><?= $product['title'] ?></td>
	<td>
		<?= $product['price'] ?>
	</td>
	<td>
			<input type="checkbox" <?= $product['inCategory'] > 0 ? 'checked' : '' ?> productId="<?= $product['id'] ?>"  id="customCheckbox<?= $product['price'] ?>" value="option<?= $product['price'] ?>" onchange="updateSection(event)">
	</td>
</tr>
<?php endforeach; ?>
