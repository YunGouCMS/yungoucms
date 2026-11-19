#!/usr/bin/env bash
# 将手机版 H5 构建结果部署到站点 /index/ 目录
# 用法：在项目根目录执行 ./scripts/deploy-mobile-web.sh

set -e
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

# 源目录（二选一）：
# - 使用 HBuilderX 发行：unpackage/dist/build/web
# - 使用 npm build:h5：dist/build/h5（需先在 mobilebefore 下执行 npm run build:h5）
if [ -d "mobilebefore/dist/build/h5" ]; then
  SRC="mobilebefore/dist/build/h5"
else
  SRC="mobilebefore/unpackage/dist/build/web"
fi

DEST="index"

if [ ! -d "$SRC" ]; then
  echo "错误：未找到构建产物目录 $SRC"
  echo "请先在 mobilebefore 下执行："
  echo "  - 使用 HBuilderX：发行 -> 网站-H5"
  echo "  - 或使用 npm：npm run build:h5"
  exit 1
fi

echo "部署来源: $SRC"
echo "部署目标: $DEST"
if command -v rsync &>/dev/null; then
  rsync -av --delete "$SRC/" "$DEST/"
else
  rm -rf "$DEST"/* "$DEST"/.[!.]* 2>/dev/null || true
  cp -a "$SRC"/* "$DEST/"
fi
echo "部署完成。请访问 /index/pages/backstage/login/login 等页面验证。"
