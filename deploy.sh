#!/bin/bash
# Script de deploiement automatise pour Boucherie Eysa
# Usage: ./deploy.sh

set -e  # Arret immediat en cas d'erreur

echo "================================"
echo "Deploiement Boucherie Eysa"
echo "================================"

echo ""
echo "[1/7] Pull des sources depuis GitHub..."
git pull origin main

echo ""
echo "[2/7] Installation des dependances npm..."
npm install --no-audit --no-fund

echo ""
echo "[3/7] Build des assets en mode production..."
NODE_ENV=production npx encore production

echo ""
echo "[4/7] Normalisation des permissions (fichiers)..."
find public/build -type f -exec chmod 644 {} \;

echo ""
echo "[5/7] Normalisation des permissions (dossiers)..."
find public/build -type d -exec chmod 755 {} \;

echo ""
echo "[6/7] Vidage du cache Symfony..."
php bin/console cache:clear --env=prod --no-warmup

echo ""<?php
class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, [
                'label' => 'Nom complet *',
                'constraints' => [
                    new Assert\class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, [
                'label' => 'Nom complet *',
                'constraints' => [
                    new Assert\
echo "[7/7] Prechauffage du cache Symfony..."
php bin/console cache:warmup --env=prod

echo ""
echo "================================"
echo "Deploiement termine avec succes"
echo "================================"
echo ""
echo "IMPORTANT: Faites un hard reload dans votre navigateur (Ctrl+Shift+R)"
echo ""
