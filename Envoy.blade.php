@servers(['web' => '127.0.0.1'])

@setup
    $repository = 'https://gitlab.hathor.at/avatric/reactiveapp.git';
    $releases_dir = '/var/www/html/reactiveapp/releases';
    $app_dir = '/var/www/html/reactiveapp';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    update_symlinks
    run_composer
    run_npm
    cleanup
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('run_composer')
    echo "Starting composer ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts -q -o --no-dev --optimize-autoloader
@endtask

@task('run_npm')
    echo "Starting npm ({{ $release }})"
    cd {{ $new_release_dir }}
    npm install
    npm run prod
@endtask

@task('update_symlinks')
    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking echo server config file'
    ln -nfs {{ $app_dir }}/laravel-echo-server.json {{ $new_release_dir }}/laravel-echo-server.json
    ln -nfs {{ $app_dir }}/laravel-echo-server.lock {{ $new_release_dir }}/laravel-echo-server.lock

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('cleanup')
    echo 'Cleanup application'
    cd {{ $new_release_dir }}

    php artisan cache:clear
    php artisan route:clear
    php artisan config:clear
    php artisan view:clear

    php artisan route:cache
    php artisan config:cache
    php artisan view:cache

    echo "Change permissions for production"
    sudo chown -R www-data:www-data {{ $new_release_dir }}

    echo 'Restart server'
    sudo /sbin/shutdown -r +1
@endtask
