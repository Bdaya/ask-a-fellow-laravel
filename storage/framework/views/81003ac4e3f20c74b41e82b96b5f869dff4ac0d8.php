<?php $__env->startSection('content'); ?>
    <form id="login_form" class="form-horizontal center-block"
          style="width: 40%; background-color: rgba(255, 175, 108, 0.64); padding: 40px; padding-right: 20px; margin-bottom: 150px; min-width: 250px;"
          method="POST" action="<?php echo e(url("/login")); ?>">
        <?php echo csrf_field(); ?>

        <span class="input input--manami">
            <input class="input__field input__field--manami" type="email" name="email" required id="login_email" value="<?php echo e(old('email')); ?>">
                <label class="input__label input__label--manami">
                    <span class="input__label-content input__label-content--manami">Email</span>
                </label>
                <?php if($errors->has('email')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                <?php endif; ?>
        </span>
        <span class="input input--manami">
            <input class="input__field input__field--manami" type="password" name="password" required id="login_password">
                <label class="input__label input__label--manami">
                    <span class="input__label-content input__label-content--manami">Password</span>
                </label>
                <?php if($errors->has('password')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('password')); ?></strong>
                    </span>
                <?php endif; ?>
        </span>
        <a class="pull-right" style="text-align:right" href="<?php echo e(url('/password/reset')); ?>">Forgot password?</a>
        <div class="form-group pull-left">
            <div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>

        </div>
        <div class="form-group">
            <div class=" col-sm-10" style="margin-top:20px; margin-left:10px;">
                <input type="submit" class="btn btn-default submit"
                       style="background-color:#FF953D; border: 1pxx solid #CC953D;float:right;" href="#" value="Login"
                       id="login_submit">
            </div>
        </div>
    </form>

    <link href="css/formInput.css" rel="stylesheet">
    <script type="text/javascript" src="js/classie.js"></script>
    <script>
        (function() {
            // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
            if (!String.prototype.trim) {
                (function() {
                    // Make sure we trim BOM and NBSP
                    var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                    String.prototype.trim = function() {
                        return this.replace(rtrim, '');
                    };
                })();
            }

            [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
                // in case the input is already filled..
                if( inputEl.value.trim() !== '' ) {
                    classie.add( inputEl.parentNode, 'input--filled' );
                }

                // events:
                inputEl.addEventListener( 'focus', onInputFocus );
                inputEl.addEventListener( 'blur', onInputBlur );
            } );

            function onInputFocus( ev ) {
                classie.add( ev.target.parentNode, 'input--filled' );
            }

            function onInputBlur( ev ) {
                if( ev.target.value.trim() === '' ) {
                    classie.remove( ev.target.parentNode, 'input--filled' );
                }
            }
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>