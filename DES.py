from bitarray import bitarray

# Initial Permutation (IP) and Inverse IP table
# IP: permutasi awal yang diterapkan pada plaintext untuk mengacak bit awalnya
IP = [58, 50, 42, 34, 26, 18, 10, 2,
      60, 52, 44, 36, 28, 20, 12, 4,
      62, 54, 46, 38, 30, 22, 14, 6,
      64, 56, 48, 40, 32, 24, 16, 8,
      57, 49, 41, 33, 25, 17, 9, 1,
      59, 51, 43, 35, 27, 19, 11, 3,
      61, 53, 45, 37, 29, 21, 13, 5,
      63, 55, 47, 39, 31, 23, 15, 7]

# IP_inv: permutasi kebalikan dari IP yang diterapkan di akhir enkripsi
IP_inv = [40, 8, 48, 16, 56, 24, 64, 32,
          39, 7, 47, 15, 55, 23, 63, 31,
          38, 6, 46, 14, 54, 22, 62, 30,
          37, 5, 45, 13, 53, 21, 61, 29,
          36, 4, 44, 12, 52, 20, 60, 28,
          35, 3, 43, 11, 51, 19, 59, 27,
          34, 2, 42, 10, 50, 18, 58, 26,
          33, 1, 41, 9, 49, 17, 57, 25]

# EXPANSION_PERMUTATION: untuk memperluas R dari 32 bit ke 48 bit sebelum XOR dengan kunci
EXPANSION_PERMUTATION = [
    32, 1, 2, 3, 4, 5, 4, 5,
    6, 7, 8, 9, 8, 9, 10, 11,
    12, 13, 12, 13, 14, 15, 16, 17,
    16, 17, 18, 19, 20, 21, 20, 21,
    22, 23, 24, 25, 24, 25, 26, 27,
    28, 29, 28, 29, 30, 31, 32, 1
]

# S_BOXES: delapan tabel S-Box untuk substitusi bit di setiap ronde
# Setiap S-Box menerima input 6 bit dan mengembalikan output 4 bit
S_BOXES = [
    # S-box 1 hingga S-box 8
    # (Setiap tabel mencakup daftar 4 baris x 16 kolom)
    # ...
]

# P_BOX: Permutasi yang digunakan setelah S-Box substitusi
P_BOX = [16, 7, 20, 21, 29, 12, 28, 17,
         1, 15, 23, 26, 5, 18, 31, 10,
         2, 8, 24, 14, 32, 27, 3, 9,
         19, 13, 30, 6, 22, 11, 4, 25]

def permute(block, table):
    """Permute block menggunakan tabel yang diberikan."""
    return bitarray([block[i - 1] for i in table])

def s_box_substitution(block):
    """Terapkan substitusi S-box pada blok 48-bit."""
    substituted_bits = bitarray()
    for i in range(8):  # Ada 8 S-boxes untuk 48-bit block
        chunk = block[i * 6:(i + 1) * 6]
        row = int(str(chunk[0]) + str(chunk[5]), 2)
        column = int(chunk[1:5].to01(), 2)
        sbox_value = S_BOXES[i][row][column]
        substituted_bits.extend(f'{sbox_value:04b}')
    return substituted_bits

def xor(bits1, bits2):
    """XOR dua bitarrays."""
    return bitarray([b1 ^ b2 for b1, b2 in zip(bits1, bits2)])

def des_encrypt(plaintext, key):
    # Ubah input plaintext dan key menjadi bitarray
    plaintext_bits = bitarray()
    plaintext_bits.frombytes(plaintext.encode('utf-8'))
    key_bits = bitarray()
    key_bits.frombytes(key.encode('utf-8'))

    # Initial Permutation (IP)
    permuted_bits = permute(plaintext_bits, IP)
    L, R = permuted_bits[:32], permuted_bits[32:]

    for round_number in range(16):  # DES memiliki 16 ronde
        # Perluas R menjadi 48-bit (Expansion Permutation)
        expanded_R = permute(R, EXPANSION_PERMUTATION)
        
        # XOR dengan kunci ronde (menggunakan sebagian dari key untuk contoh)
        round_key = key_bits[:48]  # Gunakan 48-bit pertama dari key sebagai placeholder
        A = xor(expanded_R, round_key)
        
        # Substitusi S-Box
        B = s_box_substitution(A)
        
        # Permutasi P-Box
        PB = permute(B, P_BOX)
        
        # XOR dengan L dan tukar posisi
        new_R = xor(PB, L)
        L, R = R, new_R

    # Gabungkan L dan R, lalu lakukan inversi permutasi
    combined = R + L
    ciphertext_bits = permute(combined, IP_inv)

    # Ubah bit ke format heksadesimal
    ciphertext_hex = ciphertext_bits.tobytes().hex()
    return ciphertext_hex

# Contoh plaintext dan key
plaintext = "COMPUTER"  # Plaintext harus 8 karakter untuk DES (64 bit)
key = "DESCRYPT"       # Key juga harus 8 karakter (64 bit)

# Enkripsi plaintext
ciphertext = des_encrypt(plaintext, key)
print("Ciphertext (hex):", ciphertext)