#!/bin/bash
# Install Ansible
sudo apt update && sudo apt install ansible -y
# Run the Ansible play-book to install the rest
sudo ansible-playbook ubuntu-20.04-playbook.yml