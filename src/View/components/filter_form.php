<?php

echo '
      <h2 class="inline">Hacker News Filter Table</h2>
      <form class="inline pull-right" action="/index.php" method="POST">
        <label for="operator">Operator</label>
        <select name="operator">
            <option value=">">></option>
            <option value="<"><</option>
            <option value=">=">>=</option>
            <option value="<="><=</option>
        </select>
        <label for="words"> Words</label>
        <input type="text" name="words">
        <label for="order">Order By</label>
        <select name="order">
            <option value="comments">Comments</option>
            <option value="points">Points</option>
        </select>
        <button type="submit">Filtrar</button>
      </form>';
