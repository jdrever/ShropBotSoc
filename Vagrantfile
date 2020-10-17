# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    # config.vm.box = "ubuntu/focal64" # Doesn't work for me, it can't install Ansibles.
    config.vm.box = "generic/ubuntu2004"
    config.vm.box_version = "3.0.36"
    config.vm.hostname = "captain-magenta"
    config.vm.box_check_update = true
    config.env.enable # Enable vagrant-env(`.env`) to use a `.env` file like this:

    config.vm.network "forwarded_port", guest: 8089, host: 8089 # the web application
    config.vm.network "forwarded_port", guest: 9009, host: 9009 # Xdebugging

    # The expected path to the provisioner is `/vagrant` (so provide it!)
    config.vm.synced_folder ".", "/vagrant"

    # Vagrant's name for the Virtual Machine
    config.vm.define "captain-magenta" do |la|
    end

    config.vm.provider "virtualbox" do |vb|
        vb.gui = true # it has crashed a bit so it is useful to be able to watch what is going on.
        vb.memory = "6144" # largely arbitary value
        vb.cpus = "2" # also arbitary
    end

    # Use Ansible as the provisioner (passing in the environment variables)
    config.vm.provision "ansible_local" do |ansible|
        ansible.playbook = "/vagrant/ubuntu-20.04-playbook.yml"
        ansible.install_mode = "pip" # because at the time of writing the Ubuntu repository update didn't work.
    end
end
