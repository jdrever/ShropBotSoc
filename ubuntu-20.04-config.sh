#!/bin/bash
# Install Ansible
sudo apt update && sudo apt -y install ansible
# Run the Ansible play-book to install the rest
sudo ansible-playbook ubuntu-20.04-playbook.yml --connection=local --inventory 127.0.0.1,