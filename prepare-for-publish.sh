#!/bin/bash

# Laravel Telegram Notifier - Prepare for Publishing
# Цей скрипт підготує пакет до публікації

set -e

echo "🚀 Підготовка пакету до публікації"
echo "=========================================="
echo ""

# Перевірка що скрипт запущено з правильної директорії
if [ ! -f "composer.json" ]; then
    echo "❌ Помилка: composer.json не знайдено"
    echo "   Запустіть скрипт з кореневої директорії пакету"
    exit 1
fi

echo "✓ composer.json знайдено"
echo ""

# Створення резервної копії
echo "📦 Створення резервної копії..."
BACKUP_DIR="../TelegramBot_backup_$(date +%Y%m%d_%H%M%S)"
cp -r . "$BACKUP_DIR"
echo "✓ Резервна копія створена: $BACKUP_DIR"
echo ""

# Видалення старих файлів
echo "🗑️  Видалення непотрібних файлів..."

# Видаляємо директорію app (стара структура)
if [ -d "app" ]; then
    rm -rf app/
    echo "  ✓ Видалено app/"
fi

# Видаляємо старий install.sh
if [ -f "install.sh" ]; then
    rm install.sh
    echo "  ✓ Видалено install.sh"
fi

# Видаляємо стару документацію
if [ -d "docs" ]; then
    rm -rf docs/
    echo "  ✓ Видалено docs/"
fi

# Видаляємо старі README файли
files_to_remove=(
    "HOW_TO_PUBLISH.md"
    "QUICK_INSTALL.md"
    "START_HERE.md"
    "STRUCTURE.md"
)

for file in "${files_to_remove[@]}"; do
    if [ -f "$file" ]; then
        rm "$file"
        echo "  ✓ Видалено $file"
    fi
done

# Видаляємо backup файли
rm -f *.bak *.backup
echo "  ✓ Видалено backup файли"

echo ""

# Переіменування README
echo "📝 Налаштування документації..."

if [ -f "README.md" ] && [ -f "README_PACKAGE.md" ]; then
    mv README.md README_OLD.md
    echo "  ✓ Переіменовано README.md → README_OLD.md"
fi

if [ -f "README_PACKAGE.md" ]; then
    mv README_PACKAGE.md README.md
    echo "  ✓ Переіменовано README_PACKAGE.md → README.md"
fi

echo ""

# Перевірка composer.json
echo "🔍 Перевірка composer.json..."

if grep -q '"name": "voronyuk/laravel-telegram-notifier"' composer.json; then
    echo "  ✓ Package name: voronyuk/laravel-telegram-notifier"
else
    echo "  ⚠️  Перевірте package name в composer.json"
fi

if grep -q '"Voronyuk' composer.json; then
    echo "  ✓ Namespace налаштовано правильно"
else
    echo "  ⚠️  Перевірте namespace в composer.json"
fi

echo ""

# Перевірка структури
echo "📂 Перевірка структури пакету..."

required_files=(
    "src/TelegramNotifierServiceProvider.php"
    "src/Facades/Telegram.php"
    "src/Services/TelegramNotifier.php"
    "src/helpers.php"
    "config/telegram-notifier.php"
    "composer.json"
    "LICENSE"
    "README.md"
)

all_ok=true
for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✓ $file"
    else
        echo "  ❌ Відсутній: $file"
        all_ok=false
    fi
done

echo ""

if [ "$all_ok" = true ]; then
    echo "✅ Всі необхідні файли присутні!"
else
    echo "⚠️  Деякі файли відсутні. Перевірте структуру пакету."
    exit 1
fi

echo ""
echo "=========================================="
echo "✅ Пакет готовий до публікації!"
echo "=========================================="
echo ""
echo "📝 Наступні кроки:"
echo ""
echo "1. Перевірте composer.json та оновіть дані автора (якщо потрібно):"
echo "   vim composer.json"
echo ""
echo "2. Ініціалізуйте Git:"
echo "   git init"
echo "   git add ."
echo "   git commit -m \"Initial commit: Laravel Telegram Notifier package\""
echo ""
echo "3. Створіть репозиторій на GitLab і відправте код:"
echo "   git remote add origin https://gitlab.com/your-username/laravel-telegram-notifier.git"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "4. Створіть тег версії:"
echo "   git tag -a v1.0.0 -m \"Release version 1.0.0\""
echo "   git push origin v1.0.0"
echo ""
echo "5. Зареєструйте на Packagist:"
echo "   https://packagist.org/packages/submit"
echo ""
echo "📖 Детальну інструкцію дивіться в PUBLISHING.md"
echo ""
echo "🎉 Успішної публікації!"