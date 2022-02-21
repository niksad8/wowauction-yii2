<?php
echo \yii\bootstrap4\Html::beginForm(['users/register']);
?>
    <h1>Register as a User</h1><br>
    <table class="table">
        <tbody>
        <tr>
            <td>Please enter the username : </td><td><input type="text" name="username" class="form-control"></td>
        </tr>
        <tr>
            <td>Password:</td><td><input type="text" name="password" class="form-control"></td>
        </tr>
        <tr>
            <td>Reenter Password:</td><td><input type="text" name="repassword" class="form-control"></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-success btn-block">Regsiter</button>
            </td>
        </tr>
        </tbody>
    </table>
<?php
echo \yii\bootstrap4\Html::endForm();
?>